<?php

namespace App\Providers;

use App\Models\Tag;
use App\Models\Brand;
use App\Models\Store;
use App\Models\Posting;
Use App\Models\PostingItem;
use App\Models\Category;
use App\Models\Department;
use App\Models\SubCategory;
use App\Observers\TagObserver;
use App\Observers\BrandObserver;
use App\Observers\StoreObserver;
use App\Observers\PostingObserver;
use App\Observers\PostingItemObserver;
use App\Observers\CategoryObserver;
use App\Observers\DepartmentObserver;
use Illuminate\Support\Facades\Event;
use App\Observers\SubCategoryObserver;
use Illuminate\Auth\Events\Registered;
use Illuminate\Auth\Listeners\SendEmailVerificationNotification;
use Illuminate\Foundation\Support\Providers\EventServiceProvider as ServiceProvider;

class EventServiceProvider extends ServiceProvider
{
    /**
     * The event listener mappings for the application.
     *
     * @var array
     */
    protected $listen = [
        'Illuminate\Auth\Events\Registered' => [
            'Illuminate\Auth\Listeners\SendEmailVerificationNotification',
        ],
        'App\Events\RoleHasCreated' => [
            'App\Listeners\PermissionsCreation',
        ],

        'App\Events\ModuleHasCreated' => [
            'App\Listeners\PermissionsCreationAndAssignToRoles',
        ],

        'App\Events\IncomingTransactionHasBeenCreated' => [
            'App\Listeners\GenerateIncomingTransactionCode',
        ],
    ];

    /**
     * Register any events for your application.
     *
     * @return void
     */
    public function boot()
    {
        parent::boot();

        Posting::observe(PostingObserver::class);
        PostingItem::observe(PostingItemObserver::class);
        Store::observe(StoreObserver::class);
        Tag::observe(TagObserver::class);
        Brand::observe(BrandObserver::class);
        Category::observe(CategoryObserver::class);
        SubCategory::observe(SubCategoryObserver::class);

    }
}
