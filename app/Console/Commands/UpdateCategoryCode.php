<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Category;
use Illuminate\Support\Str;

class UpdateCategoryCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'update:category-code';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update Category Code';

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
        $categorys = Category::selectedFields()
                        ->get();

        foreach($categorys as $category) {
            $category->update([
                'category_code' => $this->generateCodeSlug($category)
            ]);
        }
    }

    private function generateCodeSlug($category)
    {
        $code_slug = Str::kebab(strtolower(str_replace([",", "/" , "@", "%", "&"] , "", $category->category_name))).substr(0, 40);


        return $code_slug;
    }
}
