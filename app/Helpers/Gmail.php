<?php

namespace App\Helpers;

use Google_Client;
use Google_Service_Gmail;
use Google_Service_Gmail_Message;

class Gmail 
{
    protected $client; 
    protected $service; 
    protected $message;
    protected $from;
    protected $to;
    protected $view;
    protected $with;

    public function __construct()
    {

        $this->client = new Google_Client();
        $this->client->setAuthConfig(storage_path('googleapi-credentials.json'));
        $this->client->setSubject(env('GMAIL_ADDRESS','shopnbid@hmr.ph'));
        $this->client->setApplicationName("HMR Shop N' Bid Mailer");
        $this->client->setScopes([
                                    "https://mail.google.com/",
                                    "https://www.googleapis.com/auth/gmail.compose",
                                    "https://www.googleapis.com/auth/gmail.modify",
                                    "https://www.googleapis.com/auth/gmail.send"
                                ]);

        $this->service = new Google_Service_Gmail($this->client);

        $this->from = env('GMAIL_FROM_NAME', "HMR Shop N' Bid")."<".env('GMAIL_FROM_ADDRESS', 'shopnbid@hmr.ph').">";

    }

    public function from(string $from)
    {
        $this->from = $from;
        return $this;
    }

    public function to(string $to)
    {
        $this->to = $to;
        return $this;
    }

    public function send() 
    {
        $this->createMessage();

        dispatch(function () {
            $this->service->users_messages->send(env('GMAIL_ADDRESS','shopnbid@hmr.ph'), $this->message);
        })->afterResponse();
        

        return $this;
    }

    public function view(string $view) 
    {
        $this->view = $view;
        return $this;
    }

    public function with(array $with) 
    {
        $this->with = $with;
        return $this;
    }

    private function createMessage() 
    {
        $view = \View::make($this->view, $this->with);

        $rawMsgStr = "From: {$this->from}\r\n";
        $rawMsgStr .= "To: <{$this->to}>\r\n";
        $rawMsgStr .= 'Subject: =?utf-8?B?' . base64_encode($this->with['subject']) . "?=\r\n";
        $rawMsgStr .= "MIME-Version: 1.0\r\n";
        $rawMsgStr .= "Content-Type: text/html; charset=utf-8\r\n";
        $rawMsgStr .= 'Content-Transfer-Encoding: base64' . "\r\n\r\n";

        $rawMsgStr .= $view->render();
        // The message needs to be encoded in Base64URL
        $mime = rtrim(strtr(base64_encode($rawMsgStr), '+/', '-_'), '=');
        $this->message = new Google_Service_Gmail_Message();
        $this->message->setRaw($mime);

        return $this;
    }
}
