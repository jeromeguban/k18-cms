<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Helpers\Gmail;
use App\Models\Inquiry;
use Illuminate\Http\Request;
use App\Models\PostingInquiry;

class SellWithUsController extends Controller
{

    public function store(Request $request)
    {
        $from = Carbon::now()->subDays(7)->toDateTimeString();
        $to = Carbon::now()->toDateTimeString();

        $inquiries = PostingInquiry::selectedFields()
            ->where('type', 'Sell-with-Us')
            ->whereBetween('created_at', [$from, $to])
            ->get();

        $emails = [
            'lorie.alay@hmrphils.com',
            'david.carman@hmrphils.com',
            'joe.dawson@hmrphils.com',
            'randell.carman@hmrphils.com',
            'ron.sangil@hmrphils.com',
            'rodney.alferez@hmr.ph',
            'sharlene.carman@hmr.ph',
            'warren.carman@hmr.ph',
            'jeromeguban02@gmail.com'
        ];


        if ($inquiries->count())
            foreach ($emails as $email) {
                (new Gmail())->to(strtolower($email))
                    ->view('emails.sell-with-us-inquiries')
                    ->with([
                        'subject'       => 'Sell with Us Inquiries (Weekly Summary List)',
                        'inquiries'     => $inquiries,
                        'from'          => Carbon::parse($from)->format('M d Y'),
                        'to'            => Carbon::parse($to)->format('M d Y'),
                    ])
                    ->send();
            }
    }
}
