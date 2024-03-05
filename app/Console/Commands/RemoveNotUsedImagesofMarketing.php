<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\KeyVisual;
use App\Models\Ads;

class RemoveNotUsedImagesofMarketing extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'remove:not-used-images-of-marketing';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Remove Not Used Images of Marketing';

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
        $key_visuals = KeyVisual::where('active',0)
                        ->where('updated_at', '>=', now()->subDays(12)->toDateTimeString())
                        ->get();


        foreach ($key_visuals as $key_visual) {

            $key_visual->delete();
        }

        $ads = Ads::where('active', 0)
                        ->where('updated_at', '>=', now()->subDays(12)->toDateTimeString())
                        ->get();


        foreach ($ads as $ad) {

            $ad->delete();
        }
    }
}
