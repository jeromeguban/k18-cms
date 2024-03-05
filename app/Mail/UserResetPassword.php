<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UserResetPassword extends Mailable
{
    use Queueable, SerializesModels;
    /**
     * The http_host instance.
     *
     * @var $_SERVER['HTTP_HOST']
     */
    protected $http_host;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct($request, $http_host)
    {
        $this->user         = $request;
        $this->http_host    = $http_host;
        $this->token        = Str::random(60);

    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        DB::table('password_resets')->insert([
            'email' => $this->user->email,
            'token' => $this->token,
            'created_at' => Carbon::now()
        ]);

        return $this->view('mail.user-reset-password')
            ->with([
                'subject'       => 'Reset Password',
                'text'          => 'You are receiving this email because we received a password reset request for your account.',
                'action'        => 'http://'.$this->http_host.'/user/password-reset/' . $this->token . '?email=' . urlencode($this->user->email),
                'action_text'   => 'Click to reset your password'
            ]);
    }
}
