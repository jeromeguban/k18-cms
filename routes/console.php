<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;

/*
|--------------------------------------------------------------------------
| Console Routes
|--------------------------------------------------------------------------
|
| This file is where you may define all of your Closure based console
| commands. Each Closure is bound to a command instance allowing a
| simple approach to interacting with each command's IO methods.
|
*/

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->describe('Display an inspiring quote');

Artisan::command('generate:fees', function () {
    \App\Models\Fee::create([
        'name'          => 'Storage Charge',
        'description'   => '',
        'created_by'    => 1,
        'modified_by'   => 1
    ]);

    \App\Models\Fee::create([
        'name'          => 'Handling Out Charge',
        'description'   => '',
        'created_by'    => 1,
        'modified_by'   => 1
    ]);

    \App\Models\Fee::create([
        'name'          => 'Stripping Charge',
        'description'   => '',
        'created_by'    => 1,
        'modified_by'   => 1
    ]);

    \App\Models\Fee::create([
        'name'          => 'Handling In Charge',
        'description'   => '',
        'created_by'    => 1,
        'modified_by'   => 1
    ]);
});
