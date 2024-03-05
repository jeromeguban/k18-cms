<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->command('telescope:prune')->weekly();

        // $schedule->call(function () {
        //     (new \App\Processes\CartRemovalProcess)->handle();
        // })
        //     ->name('cart_removal')
        //     ->everyMinute()
        //     ->evenInMaintenanceMode()
        //     ->withoutOverlapping()
        //     ->runInBackground();

        $schedule->call(function () {
            (new \App\Processes\WatchlistRemovalProcess)->handle();
        })
            ->name('watchlist_removal')
            ->daily()
            ->evenInMaintenanceMode()
            ->withoutOverlapping()
            ->runInBackground();
        // Finalized Winning Bid
        $schedule->command('generate:tallied-bid-amounts ALL')
            ->name('posting_validation')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        //Check if Posting is not For approval
        $schedule->command('validate:posting-for-approval ALL')
            ->name('for_approval_validation')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('remove:duplicate-posting')
            ->name('posting_duplicate_removal')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('publish:posting-item-cache')
            ->name('publish_posting_item_cache')
            ->everyFifteenMinutes()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('unpublish:zero-quantity-posting')
            ->name('unpublish_zero_quantity_posting')
            ->daily()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('unpublish:auction-posting')
            ->name('unpublish_auction_posting')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('publish:auction-posting')
            ->name('publish_auction_posting')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // Auction Posting Cancellation

        $schedule->command('generate:auction-posting-cancellation-no-bid-amounts')
            ->name('auction_posting_cancellation_no_bid_amounts')
            ->everyThreeHours()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('generate:auction-posting-cart-cancellation')
            ->name('auction_posting_cart_cancellation')
            ->daily()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('generate:auction-posting-order-cancellation')
            ->name('auction_posting_order_cancellation')
            ->daily()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // Inherit Order Posting Details

        $schedule->command('generate:retail-order-posting-details')
            ->name('retail_order_posting_details')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('generate:auction-order-posting-details')
            ->name('auction_order_posting_details')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // Validate Pending Payment Transaction
        $schedule->command('validate:pending-payment-transactions')
            ->name('validate_pending_payment_transactions')
            ->everyMinute()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // Retail Order Cancellation
        $schedule->command('generate:retail-order-cancellation')
            ->name('generate_retail_order_cancellation')
            ->everyFourHours()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // Retail Delete Not Used Marketing Ads And Keyvisuals
        $schedule->command('remove:not-used-images-of-marketing')
            ->name('remove_not_used_images_of_marketing')
            ->daily()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();
        // Sell with Us Inquiries (Weekly Summary List)
        $schedule->command('send:weekly-sell-with-us-inquiries')
            ->name('send_weekly_sell_with_us_inquiries')
            ->weekly()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        $schedule->command('send:weekly-sell-with-us-inquiries')
            ->name('send_weekly_sell_with_us_inquiries')
            ->weeklyOn(2, '8:00')
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

         $schedule->command('delete:notifications')
            ->name('delete_notifications')
            ->daily()
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

        // $schedule->command('update:google-merchant-products')
        //     ->name('update_google_merchant_products')
        //     ->dailyAt('13:00')
        //     ->runInBackground()
        //     ->withoutOverlapping()
        //     ->evenInMaintenanceMode();

        $schedule->command('delete:google-merchant-products')
            ->name('delete_google_merchant_products')
            ->dailyAt('13:00')
            ->runInBackground()
            ->withoutOverlapping()
            ->evenInMaintenanceMode();

    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__ . '/Commands');

        require base_path('routes/console.php');
    }
}
