<?php

namespace App\Console\Commands;

use Carbon\Carbon;
use App\Models\Notification;
use Illuminate\Console\Command;

class DeleteNotifaction extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'delete:notifications';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Delete Notifications';

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
        Notification::where('created_at', '<', Carbon::now()->subDays(29))->delete();

        $this->comment('Send Successfully!');
    }
}