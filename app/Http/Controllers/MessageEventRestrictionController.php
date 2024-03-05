<?php

namespace App\Http\Controllers;

use carbon\Carbon;
use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class MessageEventRestrictionController extends Controller
{
    /**
     * Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function block(Participant $participant)
    {
      
        $participant->update([
            'message_restricted_at'  => Carbon::now(),
            'modified_by'    => auth()->id()
        ]);

        Redis::set("events_".$participant->event_id."_customers_".$participant->customer_id."_blocked_from_messaging", true);

        activity()
            ->performedOn( $participant)
            ->withProperties($participant)
            ->log('Participant has been Blocked to Messages');

        return [
            'success' => 1,
            'data' => $participant
        ];
    }

     /**
     * Update a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function unblock(Participant $participant)
    {
        
        $participant->update([
            'message_restricted_at'  => null,
            'modified_by'    => auth()->id()
        ]);

        Redis::set("events_".$participant->event_id."_customers_".$participant->customer_id."_blocked_from_messaging", false);

        activity()
            ->performedOn( $participant)
            ->withProperties($participant)
            ->log('Participant has been Unblocked to Messages');

        return [
            'success' => 1,
            'data' => $participant
        ];
    }
}
