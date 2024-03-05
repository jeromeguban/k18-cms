<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Courier;
use App\Models\StoreCourier;
use App\Console\Commands\Data\CourierData;
use App\Console\Commands\Data\StoreCourierData;

class GenerateCouriers extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:couriers';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate Couriers and Store Couriers';

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
        $createdCouriers = collect();

        if (!Courier::count()) {
            $createdCouriers = (new CourierData)->createCouriers();

            if ($createdCouriers->isNotEmpty()) {
                $this->comment('Couriers Generated Successfully');
                
            } else {
                $this->comment('No couriers were generated.');
            }
        } else {
            $this->comment('Couriers Already Exists');
        }

        if (!StoreCourier::count()) {
            $createdStoreCouriers = (new StoreCourierData)->createStoreCouriers($createdCouriers);

            if ($createdStoreCouriers->isNotEmpty()) {
                $this->comment('Store Couriers Generated Successfully');
                
            } else {
                $this->comment('No store couriers were generated.');
            }
        } else {
            $this->comment('Store Couriers Already Exists');
        }
    }
}
