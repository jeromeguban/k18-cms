<?php

namespace App\Console\Commands;

ini_set('memory_limit','-1');
ini_set('max_execution_time', 14400);
use App\Models\Posting;
use App\Models\PostingFilter;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Symfony\Component\Console\Helper\ProgressBar;

class GeneratePostingFilter extends Command
{
    protected $posting_filter, $postings;
    
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'generate:posting-filter';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create Posting Filter for ShopNBid';

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


       $this->postings = Posting::whereNull('deleted_at')->get();

       $bar = new ProgressBar($this->output, count($this->postings));

       $bar->setFormat("Processing: %current%/%max% [%bar%] %remaining%");

       $bar->start();      
      
       foreach( $this->postings as $posting) {

         try {

                \DB::transaction(function()use($posting){

                    $this->createPostingFilterCategories($posting);
                    $this->createPostingFilterSubCategories($posting);
                    $this->createPostingFilterBrands($posting);

                });
                 
            }
            catch (\Exception $e) {
                    
         }

            $bar->advance();
        }

        $bar->finish();
        print "\n";
        $this->comment('Posting Filters Successfully Generated!');
           
    }  

    private function createPostingFilterCategories($posting) {

        $categories  = json_decode($posting->categories);
        $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();
 
        if($categories && !$posting_filters->count()){
 
             foreach($categories as $category){
 
                 PostingFilter::updateOrCreate([

                        'posting_id' => $posting->posting_id,
                        'filter_type' => "categories",
                        'filter_id'   => $category,
        
                    ],[
                        'created_by' => 1,
                        'published_date' => $posting->published_date,
                 ]);
             }
         }
    }
    

     private function createPostingFilterSubCategories($posting) {

        $sub_categories  = json_decode($posting->sub_categories);
        $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();

        if($sub_categories && !$posting_filters->count()){

                foreach($sub_categories as $sub_category){
                
                    PostingFilter::updateOrCreate([

                        'posting_id' => $posting->posting_id,
                        'filter_type' => "sub_categories",
                        'filter_id'   => $sub_category,

                    ],[
                        'created_by' => 1,
                        'published_date' => $posting->published_date,
                    ]);
                }
            }
        }

    private function createPostingFilterBrands($posting) {

        $brands  = json_decode($posting->brands);
        $posting_filters = PostingFilter::where('posting_id', $posting->posting_id)->get();

        if($brands && !$posting_filters->count()){

                foreach($brands as $brand){

                    PostingFilter::updateOrCreate([

                        'posting_id' => $posting->posting_id,
                        'filter_type' => "brands",
                        'filter_id'   => $brand,

                    ],[
                        'created_by' => 1,
                        'published_date' => $posting->published_date,
                    ]);
                }
            }
        }
}
