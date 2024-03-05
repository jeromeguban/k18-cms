<?php

namespace App\Console\Commands;

use App\Models\CustomerRegistration;
use App\Models\CustomerLoginCredential;
use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;

class ReplaceSmartPldtCustomerEmail extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'replace:smart-pldt-customer-email {event_id}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Replace Customer Email from Smart and Pldt Event';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $customer_registrations = CustomerRegistration::where('event_id', $this->argument('event_id'))
                                        ->get();

        $bar = new ProgressBar($this->output, count($customer_registrations));

        $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

        $bar->start();

        foreach($customer_registrations as $index => $customer_registration) {

            $customer_login_credential = CustomerLoginCredential::where('email_index', $customer_registration->email_index)
                                            ->where('username_index', $customer_registration->username_index)
                                            ->first();

            if(!$customer_login_credential)
                continue;
            
            $customer_login_credential->update([
                'email'        => encrypt(strtolower("-")),
                'email_index'  => md5(strtolower("-")),
            ]);

            $bar->advance();

        }

        $bar->finish();
    }
}
