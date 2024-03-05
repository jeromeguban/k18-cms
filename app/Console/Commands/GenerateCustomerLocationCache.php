<?php

namespace App\Console\Commands;

use App\Models\Address;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Redis;
use Symfony\Component\Console\Helper\ProgressBar;

class GenerateCustomerLocationCache extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:customer-location-cache';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Customer Location Cache';

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
        
        $addresses = Address::whereNull('deleted_at')->where('default', 1)->get();

        $bar = new ProgressBar($this->output, count($addresses));

        $bar->setFormat("Processing Bidder Numbers: %current%/%max% [%bar%] %remaining%");

        $bar->start();   

        foreach($addresses as $address) {
            Redis::set("customer_".$address->customer_id."_location", $address->city);
            $bar->advance();
        }

        $bar->finish();
        print "\n";

        $this->comment(' Customer Location Cached Successfully Generated!');
    }
}
