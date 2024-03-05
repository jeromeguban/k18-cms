<?php

namespace App\Providers;

use Illuminate\Support\Facades\Gate;
use Laravel\Telescope\IncomingEntry;
use Laravel\Telescope\Telescope;
use Laravel\Telescope\TelescopeApplicationServiceProvider;

class TelescopeServiceProvider extends TelescopeApplicationServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Telescope::night();

        $this->hideSensitiveRequestDetails();

        Telescope::tag(function (IncomingEntry $entry) {

            if($entry->type === 'request' && strpos($entry->content['uri'], '/payment-gateway/callback') !== false)
                return ['api:payment-gateway', 'api:payment-gateway:'.$entry->content['payload']['req_id']];
        });

        Telescope::filter(function (IncomingEntry $entry) {
            if (env('TELESCOPE_KEY', false) || $this->app->environment('local')) {
                return true;
            }

            if($entry->type === 'request' && strpos($entry->content['uri'], '/payment-gateway/callback') !== false && $entry->content['response_status'] != 200){
                return true;
            }

            return $entry->isReportableException() ||
                   $entry->isFailedRequest() ||
                   $entry->isFailedJob() ||
                   $entry->isScheduledTask() ||
                   $entry->hasMonitoredTag();
        });
    }

    /**
     * Prevent sensitive request details from being logged by Telescope.
     *
     * @return void
     */
    protected function hideSensitiveRequestDetails()
    {
        if ($this->app->environment('local')) {
            return;
        }

        Telescope::hideRequestParameters(['_token']);

        Telescope::hideRequestHeaders([
            'cookie',
            'x-csrf-token',
            'x-xsrf-token',
        ]);
    }

    /**
     * Register the Telescope gate.
     *
     * This gate determines who can access Telescope in non-local environments.
     *
     * @return void
     */
    protected function gate()
    {
        Gate::define('viewTelescope', function ($user) {
            return in_array($user->email, [
                'jerome.guban@hmrphils.com',
                'devteam@hmrphils.com',
                'devteam2@hmrphils.com'
            ]);
        });
    }
}
