<?php

namespace App\Http\Controllers;

use App\Helpers\Gmail;
use App\Models\PostingInquiry;
use Illuminate\Http\Request;

class SendEmailController extends Controller
{

    public function index(Request $request)
    {

        (new Gmail())->to(strtolower($request->email))
            ->view('emails.inquiry')
            ->with([
                'email' => $request->email,
                'subject' => $request->subject,
                'message' => $request->message,
            ])
            ->send();

        if ($request->inquiry_id) {
            $posting_inquiry = PostingInquiry::where('id', $request->inquiry_id)->first() ?? null;

            if ($posting_inquiry && $request->inquiry_id) {

                activity()
                    ->performedOn($posting_inquiry)
                    ->withProperties($posting_inquiry)
                    ->log('Email Successfully Sent to' . ' ' . $request->email);
            }

        }

    }
}
