<?php

namespace App\Console\Commands;

use App\Helpers\Gmail;
use App\Helpers\ModelDecrypter;
use App\Models\PostingInquiry;
use App\Models\PostingInquiryTask;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;

class SendWeeklySellWithUsInquiries extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'send:weekly-sell-with-us-inquiries';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send Weekly Sell With Us Inquiries';

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
     * @return mixed
     */
    public function handle()
    {
        $from = Carbon::now()->subDays(7)->toDateTimeString();
        $to = Carbon::now()->toDateTimeString();

        $inquiries = PostingInquiry::selectedFields()
            ->leftJoinAccountExecutive()
            ->where('type', 'Sell-with-Us')
        // ->where('posting_inquiries.id', 420)
            ->whereBetween('posting_inquiries.created_at', [$from, $to])
            ->get();

        $tasks = PostingInquiryTask::selectedFields()
            ->whereIn('posting_inquiry_tasks.posting_inquiry_id', $inquiries->pluck('id'))
            ->get();

        $inquiries = (new ModelDecrypter)->decryptCollection($inquiries);
        $emails = [
            'lorie.alay@hmrphils.com',
            'david.carman@hmrphils.com',
            // 'joe.dawson@hmrphils.com',
            'randell.carman@hmrphils.com',
            // 'ron.sangil@hmrphils.com',
            'rodney.alferez@hmr.ph',
            'sharlene.carman@hmr.ph',
            'warren.carman@hmr.ph',
            'jeromeguban02@gmail.com',
        ];

        if ($inquiries->count()) {
            foreach ($emails as $email) {
                (new Gmail())->to(strtolower($email))
                    ->view('emails.sell-with-us-inquiries')
                    ->with([
                        'subject' => 'Sell with Us Inquiries (Weekly Summary List)',
                        'inquiries' => $inquiries,
                        'tasks' => $tasks,
                        'from' => Carbon::parse($from)->format('M d Y'),
                        'to' => Carbon::parse($to)->format('M d Y'),
                    ])
                    ->send();
            }
        }

        $this->comment('Send Successfully!');
    }
}
