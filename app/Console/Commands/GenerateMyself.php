<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Store;
use App\Models\Posting;
use Illuminate\Support\Str;
use Illuminate\Console\Command;
use App\Models\Searchable\Posting as SearchablePosting;

class GenerateMyself extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:myself';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate SUDO';

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

        $postings = Posting::where('postings.auction_id', 2936)->whereNull('deleted_at')->get();

            if($postings) {
                foreach($postings as $posting) {
                    $posting->create([
                        'slug'                 => Str::kebab(strtolower($this->generateRandomTitle())),
                        'name'                 => $this->generateRandomTitle(),
                        'item_id'              => null,
                        'description'          => $posting->description,
                        'extended_description' => $posting->extended_description,
                        'location'             => $posting->location,
                        'banner'               => $posting->banner,
                        'images'               => $posting->images,
                        'categories'           => $posting->categories,
                        'sub_categories'       => $posting->sub_categories,
                        'auction_category'               => $posting->auction_category,
                        'quantity'               => $posting->quantity,
                        'lot_id'               => $posting->lot_id,
                        'auction_number'               => $posting->auction_number,
                        'term_id'               => $posting->term_id,
                        'auction_id'               => $posting->auction_id,
                        'lot_id'               => $posting->lot_id,
                        'auction_name'               => $posting->auction_name,
                        'starting_time'               => $posting->starting_time,
                        'ending_time'               => $posting->ending_time,
                        'payment_period'               => $posting->payment_period,
                        'buyers_premium'               => $posting->buyers_premium,
                        'starting_amount'               => $posting->starting_amount,
                        'brands'               => null,
                        'seo_keywords'         => null,
                        'category'             => 'Auction',
                        'tags'                 => null,
                        'bidding'              => 1,
                        'buy_now'              => 0,
                        'pickup'               => 1,
                        'delivery'             => 0,
                        'unit_price'           => $posting->unit_price ?? 0 ,
                        'length'               => $posting->length ?? 0,
                        'width'                => $posting->width ?? 0,
                        'height'               => $posting->height ?? 0,
                        'weight'               => $posting->weight ?? 0,
                        'suggested_retail_price' => $posting->suggested_retail_price,
                        'store_id'                => $posting->store_id,
                        'created_by'              => 1,
                        'modified_by'             => 1,
                        'published_by'            => 1,
                        'published_date'          => $posting->published_date,
                        'category_sequence'       => 2
                    ]);

                    // SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
                }
            }

        // $account = User::where('email', 'devteam@hmrphils.com')->first();
        // if(!$account){

        //     $stores = Store::all();
        //     if(!$stores->count())
        //         $stores = $this->createStores();
           
        //     $user = User::create([
        //         'first_name'    => 'Dev',
        //         'last_name'     => 'Team',
        //         'phone'         => '09178031175',
        //         'email'         => 'devteam@hmrphils.com',
        //         'password'      => bcrypt('qweqwe'),
        //         'status'        => 'Approved',
        //         'created_by'    => 0,
        //         'modified_by'   => 0,
        //     ]);

        //     $this->comment('Yay! Awesome you created Dev Team!');    
        //     return;
        // }
        // $this->comment('Dev Team Already Exists!');
    }

    private function generateRandomTitle($numWords = 1) {
        $words = ['Test 13', 'Test 14', 'Test 15', 'Test 16', 'Test 17', 'Test 18', 'Test 19', 'Test 20', 'Test 21', 'Test 22', 'Test 23', 'Test 24', 'Test 25'];
        shuffle($words);
    
        return implode(' ', array_slice($words, 0, $numWords));
    }

    private function generateRandomText($numSentences = 5) {
        $words = ['Test 6', 'Test 7', 'Test 8', 'Test 9', 'Test 10', 'Test 11', 'Test 12', 'Test 13'];
        $text = '';
    
        for ($i = 0; $i < $numSentences; $i++) {
            shuffle($words);
            $sentenceLength = rand(4, 10);
            $sentence = ucfirst(implode(' ', array_slice($words, 0, $sentenceLength)));
            $text .= rtrim($sentence, ' ,') . '. ';
        }
    
        return $text;
    }
    
    private function createStores()
    {
        // $default_stores = [
        //     [
        //         'reference_id'			=> 1,
        //         'code'					=> 'WSR',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'West Service Road',
        //         'slug'					=> Str::kebab(strtolower('West Service Road')),
        //         'address_line'			=> 'KM 21 Villonco, cor, 1770 W Service Rd, Muntinlupa, Metro Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> '(02)548 6962',
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 2,
        //         'code'					=> 'DAU',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'DAU',
        //         'slug'					=> Str::kebab(strtolower('DAU')),
        //         'address_line'			=> 'ETASI Warehouse, Brgy. Duquit, Dau, Mabalacat, Pampanga',
        //         'extended_address'		=> null,
        //         'contact_number'		=> '0917 822 3599',
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 3,
        //         'code'					=> 'ONS',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Onsite',
        //         'slug'					=> Str::kebab(strtolower('Onsite')),
        //         'address_line'			=> 'KM 21 Villonco, cor, 1770 W Service Rd, Muntinlupa, Metro Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> '(02)548 6962',
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 4,
        //         'code'					=> 'AUTO',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Auto Auction',
        //         'slug'					=> Str::kebab(strtolower('Auto Auction')),
        //         'address_line'			=> 'ACSIE Compound, Main Avenue, KM16 West Service Road, Bicutan, Paranaque, Metro Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> '(02)548 6962',
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 5,
        //         'code'					=> 'CW',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Central Warehouse',
        //         'slug'					=> Str::kebab(strtolower('Central Warehouse')),
        //         'address_line'			=> 'Km20, Unit 3-4 Brangay Buli Concepcion Street, Muntinlupa City',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 7,
        //         'code'					=> 'RE',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Real Estate',
        //         'slug'					=> Str::kebab(strtolower('Real Estate')),
        //         'address_line'			=> 'HMR Sucat, Dr A. Santos Ave, San Antonio, Parañaque',
        //         'extended_address'		=> null,
        //         'contact_number'		=> '0285486999',
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 8,
        //         'code'					=> 'DEV',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'HMRDEVZ TEST WAREHOUSE',
        //         'slug'					=> Str::kebab(strtolower('HMRDEVZ TEST WAREHOUSE')),
        //         'address_line'			=> 'TEST WAREHOUSE',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 9,
        //         'code'					=> 'HRL',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'HRL Warehouse',
        //         'slug'					=> Str::kebab(strtolower('HRL Warehouse')),
        //         'address_line'			=> 'HRL Warehouse Km 23 West Service Road, Alabang Muntinlupa',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 10,
        //         'code'					=> 'MAS',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Mega Auction Showroom',
        //         'slug'					=> Str::kebab(strtolower('Mega Auction Showroom')),
        //         'address_line'			=> 'HMR Auctions Showroom, Day Star Industrial Park, Bo. Pulong Sta. Cruz, Sta. Rosa Laguna Laguna',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 11,
        //         'code'					=> 'ARA',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'HMR Araneta',
        //         'slug'					=> Str::kebab(strtolower('HMR Araneta')),
        //         'address_line'			=> 'Telus, General Mc Arthur Ave, Cubao, Quezon City, 1109 Metro Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 12,
        //         'code'					=> 'ILO',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Iloilo',
        //         'slug'					=> Str::kebab(strtolower('Iloilo')),
        //         'address_line'			=> 'Telus, General Mc Arthur Ave, Cubao, Quezon City, 1109 Metro Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 13,
        //         'code'					=> 'CEB',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Cebu',
        //         'slug'					=> Str::kebab(strtolower('Cebu')),
        //         'address_line'			=> 'SM Hypermarket, M. Logarta Avenue,, North Reclamation Area, Brgy. Subangdaku,, Mandaue City,, 6014 Cebu',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 14,
        //         'code'					=> 'CDO',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'Cagayan De Oro',
        //         'slug'					=> Str::kebab(strtolower('Cagayan De Oro')),
        //         'address_line'			=> 'Limketkai Dr, Cagayan de Oro, Misamis Oriental',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'			=> 15,
        //         'code'					=> 'INTL',
        //         'store_company_name'	=> 'HMR Auctions Services INC',
        //         'store_name'			=> 'International Stock Lots',
        //         'slug'					=> Str::kebab(strtolower('International Stock Lots')),
        //         'address_line'			=> 'Manila',
        //         'extended_address'		=> null,
        //         'contact_number'		=> null,
        //         'store_type'			=> 'HMR Auctionss',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ],[
        //         'reference_id'          => 4,
        //         'code'                  => 'PIO',
        //         'store_company_name'    => 'HMR PHILIPPINES',
        //         'store_name'            => 'PIONEER',
        //         'slug'                  => Str::kebab(strtolower('PIONEER')),
        //         'address_line'          => 'Pioneer St. Cor Reliance St.,',
        //         'extended_address'      => 'Highway Hills, Mandaluyong City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 7,
        //         'code'                  => 'ARA',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'ARANETA',
        //         'slug'                  => Str::kebab(strtolower('ARANETA')),
        //         'address_line'          => 'Telus Bldg. Araneta Center',
        //         'extended_address'  	=> 'Cubao, Quezon City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 8,
        //         'code'                  => 'FES',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'FESTIVAL',
        //         'slug'                  => Str::kebab(strtolower('FESTIVAL')),
        //         'address_line'          => '2nd level Festival Mall, Alabang',
        //         'extended_address'      => 'City of Muntinlupa',
        //         'contact_number'		=> null,
        //         'store_type'            => 'Save On Surplus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 11,
        //         'code'                  => 'NOV',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'NOVALICHES',
        //         'slug'                  => Str::kebab(strtolower('NOVALICHES')),
        //         'address_line'          => 'G/F SM Hypermarket Quirino Highway',
        //         'extended_address'      => 'Talipapa2, Quezon City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 12,
        //         'code'                  => 'ECW',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'LORO COMMONWEALTH',
        //         'slug'                  => Str::kebab(strtolower('LORO COMMONWEALTH')),
        //         'address_line'          => '2/F Ever Gotesco Commonwealth Ave.',
        //         'extended_address'  	=> 'Brgy. Batasan Hills Quezon City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'Save On Surplus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 14,
        //         'code'                  => 'SUB',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'SUBIC MAIN',
        //         'slug'                  => Str::kebab(strtolower('SUBIC MAIN')),
        //         'address_line'          => 'Bldg. 1109 Palms St., Gateway District,',
        //         'extended_address'  	=> 'Subic Bay Free Port Zone',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 15,
        //         'code'                  => 'BAT',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'BATANGAS',
        //         'slug'                  => Str::kebab(strtolower('BATANGAS')),
        //         'address_line'          => 'SM Hypermarket, Balagtas',
        //         'extended_address'  	=> 'Batangas City, Batangas',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 18,
        //         'code'                  => 'DSM',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'SOS DASMARINAS',
        //         'slug'                  => Str::kebab(strtolower('SOS DASMARINAS')),
        //         'address_line'          => 'Unit 1, Dasmarinas Commercial Complex',
        //         'extended_address'  	=> "Governor's Dr, San Agustin, Dasmarinas Cavite",
        //         'contact_number'		=> null,
        //         'store_type'            => 'Save On Surplus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 19,
        //         'code'                  => 'CEB',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'CEBU',
        //         'slug'                  => Str::kebab(strtolower('CEBU')),
        //         'address_line'          => 'Inside SM Hypermarket, Logarta Ave.,',
        //         'extended_address'  	=> 'Subangdaku, Mandaue City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 20,
        //         'code'                  => 'MKT',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'MARKET MARKET',
        //         'slug'                  => Str::kebab(strtolower('MARKET MARKET')),
        //         'address_line'          => 'L3 332 3/F Market! Market!',
        //         'extended_address'  	=> 'Bonifacio Global City, Taguig City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 22,
        //         'code'                  => 'MAB',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'MABALACAT',
        //         'slug'                  => Str::kebab(strtolower('MABALACAT')),
        //         'address_line'          => 'SM Hypermarket, Mc Arthur Highway,',
        //         'extended_address'  	=> 'Camachiles, Mabalacat City, Pampanga',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 23,
        //         'code'                  => 'FVT',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'FAIRVIEW',
        //         'slug'                  => Str::kebab(strtolower('FAIRVIEW')),
        //         'address_line'          => '2nd Lvl, Fairview Terraces, Quirino Hi-way',
        //         'extended_address'  	=> 'Maligaya Drive, Novaliches, Quezon City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 39,
        //         'code'                  => 'CUB',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR CUBAO',
        //         'slug'                  => Str::kebab(strtolower('HMR CUBAO')),
        //         'address_line'          => 'SM Hypermarket, Cubao Main Ave.',
        //         'extended_address'  	=> 'Cor. EDSA, Socorro 3,, Quezon City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 41,
        //         'code'                  => 'ILO',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR ILOILO',
        //         'slug'                  => Str::kebab(strtolower('HMR ILOILO')),
        //         'address_line'          => 'Inside SM Hypermarket, Comm. Civil',
        //         'extended_address'  	=> 'Cor Jalandoni St., Jaro Iloilo City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 42,
        //         'code'                  => 'ANT',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR ANTIPOLO',
        //         'slug'                  => Str::kebab(strtolower('HMR ANTIPOLO')),
        //         'address_line'          => '2/F L52-05 Xentro Mall Sumulong',
        //         'extended_address'  	=> 'Highway, Mambugan, Antipolo City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 44,
        //         'code'                  => 'HCM',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR CIRCUIT',
        //         'slug'                  => Str::kebab(strtolower('HMR CIRCUIT')),
        //         'address_line'          => 'L3 055 A.P. Reyes St. Cor Hippodromo',
        //         'extended_address'  	=> 'Ayala Malls Circuit Carmona Makati City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 53,
        //         'code'                  => 'SRR',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR TAGAYTAY ROAD',
        //         'slug'                  => Str::kebab(strtolower('HMR TAGAYTAY ROAD')),
        //         'address_line'          => 'DAYSTAR STA. ROSA INDUSTRIAL PARK',
        //         'extended_address'  	=> 'PULONG STA. CRUZ, STA. ROSA LAGUNA',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 54,
        //         'code'                  => 'HSR',
        //         'store_company_name'	=> 'HMR RETAIL HAUS',
        //         'store_name'            => 'HMR SUCAT',
        //         'slug'                  => Str::kebab(strtolower('HMR SUCAT')),
        //         'address_line'          => 'Dr. A. Santos Ave.Brgy. San Isidro',
        //         'extended_address'  	=> 'Paranaque City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 55,
        //         'code'                  => 'CDO',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR CAGAYAN DE ORO',
        //         'slug'                  => Str::kebab(strtolower('HMR CAGAYAN DE ORO')),
        //         'address_line'          => 'Unit 16 Puregold Bldg., National Highway',
        //         'extended_address'  	=> 'Lapasan, Cagayan De Oro City',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 56,
        //         'code'                  => 'JCB',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR BULACAN',
        //         'slug'                  => Str::kebab(strtolower('HMR BULACAN')),
        //         'address_line'          => 'Km46 Mc Arthur Highway Pio Cruzcosa',
        //         'extended_address'  	=> ' Calumpit, Bulacan',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 60,
        //         'code'                  => 'AOL',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HMR LAS PINAS ONLINE',
        //         'slug'                  => Str::kebab(strtolower('HMR LAS PINAS ONLINE')),
        //         'address_line'          => 'Km46 Mc Arthur Highway Pio Cruzcosa',
        //         'extended_address'  	=> ' Calumpit, Bulacan',
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ], [
        //         'reference_id'          => 66,
        //         'code'                  => 'HBP',
        //         'store_company_name'	=> 'HMR PHILIPPINES',
        //         'store_name'            => 'HARBOR POINT',
        //         'slug'                  => Str::kebab(strtolower('HARBOR POINT')),
        //         'address_line'          => 'Subic Harbor Point',
        //         'extended_address'      => null,
        //         'contact_number'		=> null,
        //         'store_type'            => 'HMR Retail Haus',
        //         'created_by'            => 1,
        //         'modified_by'           => 1,
        //         'created_at'            => now(),
        //         'updated_at'            => now(),
        //     ]
        // ];

        // $stores = [];

        // foreach($default_stores as $store) {
        //     $stores[] = Store::create($store);
        // }

        // return collect($stores);

    }
}
