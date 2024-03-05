<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use App\Models\Event;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Artisan;

class RefreshEventCacheController extends Controller
{
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function refresh(Event $event)
    {
        if(!$event->published_date){
            abort(403, "Not Published");
        }

        if($event->published_date){
            $commandName = 'generate:event-posting-cache';
            
            $parameters = [
                'event' => $event->event_id,
            ];
    
            $exitCode = Artisan::call($commandName, $parameters);
    
            if ($exitCode === 0) {
                return 'Command executed successfully.';
            } else {
                return 'Command failed.';
            }
        }  
    }
}
