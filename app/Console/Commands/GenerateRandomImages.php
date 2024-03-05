<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use App\Models\Posting;
use App\Jobs\ItemBidderNumberJob;

class GenerateRandomImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:random-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

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
        $images = ['e8xk2axfal8wjfxnc.jpg',
        'e8xk2w2fhl709qya9.jpg',
        'e8xk21bbtulbg91vof.jpg',
        'e8xk21cwy6le0wrsgq.jpg',
        'e8xk21cwywle0wvtce.jpg',
        'e8xk21cwzole0wjfks.jpg',
        'e8xk21cwzule29934w.jpg',
        'e8xk21cx1ile29btiy.jpg',
        'e8xk21cx3ale29hv5y.jpg',
        'e8xk21cx5wle29fa5x.jpg',
        'e8xk21m1a3lblmgc0g.jpg',
        'e8xk22ugvlf6f2i2f.jpg',
        'e8xk22ugvlf50gad8.jpg',
        'e8xk22uhllf6dudve.jpg',
        'e8xk22ui7lf569n42.jpg',
        'e8xk22uidlf6am5dj.jpg',
        'e8xk22uidlf6bezvwjpg',
        'e8xk22uivlf5732ed.jpg',
        'e8xk22uiwlf6dz1r8.jpg',
        'e8xk22uiwlf57v5bs.jpg',
        'e8xk22ukvlf53he02.jpg',
        'e8xk22ul1lf6dbxbm.jpg',
        'e8xk22ulqlf6da4aw.jpg',
        'e8xk22um3lf6btyhn.jpg',
        'e8xk22um3lf67hibj.jpg',
        'e8xk22unqlf6ctusf.jpg',
        'e8xk22uphlf6fyqf6.jpg',
        'e8xk22uqglf56po2q.jpg',
        'e8xk22ur4lf4twq2c.jpg',
        'e8xk22ur4lf6aq8fm.jpg',
        'e8xk22ur4lf6g0gag.jpg',
        'e8xk22ur4lf69z1t5.jpg',
        'e8xk28hxilf6gmfzx.jpg',
        'e8xk28i0olf6gzouo.jpg',
        'e8xk28i4rlf6h4vr1.jpg',
        'e8xk28i7elf6gtefc.jpg',
        'e8xk210iyildidv30t.jpg'];

        if(env('DB_HOST') != '192.168.149.52'){
            $postings = Posting::selectedFields()
            ->whereNull('deleted_at')
            // ->whereNotNull('published_date')
            ->whereNull('auction_id')
            ->get();

            $bar = new ProgressBar($this->output, count($postings));

            $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

            $bar->start();

            foreach($postings as $posting){
                $index = rand(0,36);
                $image = $images[$index];
                $posting->update([
                'banner' => '/images/postings/2023/03/'.$image,
                'images' => json_encode(['/images/postings/2023/03/'.$image]),
                ]);

                $bar->advance();
            }

        }
        
        $bar->finish();
            print "\n";
            $this->comment('Posting Images Successfully Generated');
    }
}
