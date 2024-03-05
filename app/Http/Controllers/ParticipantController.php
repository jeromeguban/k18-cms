<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;


class ParticipantController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Event $event)
    {
        $query = Participant::selectedFields()
                    ->joinCustomer()
                    ->joinCustomerLoginCredential()
                    ->where('event_id', $event->event_id)
                    // ->whereNotNull('agree_date')
                    ->orderBy('participants.created_at')
                    ->get();

        return (new ModelDecrypter)->decryptCollection($query)->toJson();
    }
}
