<?php

namespace App\Processes;

use Carbon\Carbon;
use App\Models\Event;
use App\Models\Posting;
use Illuminate\Support\Str;
use App\Helpers\GuzzleRequest;
use Illuminate\Support\Facades\DB;
use App\Models\EventAccessRequestTemplate;
use App\Models\Searchable\Posting as SearchablePosting;

class EventProcess
{
	protected $request, $event, $email_template_approved, $email_template_declined;

	/**
     * Create a new process instance.
     *
     * @return void
     */
	public function __construct($request = null)
	{

		$this->request = $request ? (object) $request : request();

	}

	/**
     * Execute find process.
     *
     * @return void
    */

    public function find($id)
    {

    	$this->event = Event::findOrFail($id);

    	return $this;

    }

    /**
     * Retrive event.
     *
     * @return
    */

    public function event()
    {

    	return $this->event;

    }

    public function create()
    {
    	DB::transaction(function(){
    		$this->createEvent()
                ->createEmailTemplate();

            $this->createStream();
    	});
    }

    public function update()
    {
    	DB::transaction(function(){
    		$this->updateEvent();
            $this->inheritToPosting();
            $this->createStream();
    	});
    }

    public function createEvent()
    {
        // if($this->request->valid_domains != null)
        //     $valid_domains = array_merge(json_encode($this->request->valid_domains, true), array($this->request->valid_domains));

        // str_replace("{", "", str_replace(`""`, "" str_replace(":", "", str_replace("domain", "", json_encode($this->request->valid_domains)))))

    	$this->event = Event::create([
    		'event_name'		=> $this->request->event_name,
            'slug'              => $this->generateSlug(),
    		'description'		=> $this->request->description,
    		'starting_time'		=> Carbon::parse($this->request->starting_time)->toDateTimeString(),
    		'purchase_limit'	=> $this->request->purchase_limit,
    		'term_id'			=> $this->request->term_id,
    		'type'				=> $this->request->type,
            'access_request_info'   => json_encode($this->request->access_request_info) ?? null,
            'valid_domains'      => str_replace(["{", "}"], "", json_encode($this->request->valid_domains)) ?? [],
            'restriction'       => $this->request->restriction ? 1 : 0,
            'auto_approve'      => $this->request->auto_approve ? 1 :0,
            'category'          => $this->request->category,
            'top_up'            => $this->request->top_up ? 1 : 0,
            'top_up_amount'     => $this->request->top_up_amount,
            'stream_id'         => $this->request->stream_id,
            'stream_type'       => $this->request->stream_type,
            'rtmp_url'          => $this->request->rtmp_url,
            'stream_response'   => $this->request->stream_response,
            'store_id'          => session()->get('store_id'),
            'store_id'          => session()->get('store_id'),
    		'created_by'		=> auth()->user()->id,
    		'modified_by'		=> auth()->user()->id,
    	]);

    	return $this;
    }

    public function updateEvent()
    {
    	$this->event->update([
    		'event_name'		=> $this->request->event_name,
            'slug'              => $this->generateSlug(),
    		'description'		=> $this->request->description,
    		'starting_time'		=> Carbon::parse($this->request->starting_time)->toDateTimeString(),
    		'purchase_limit'	=> $this->request->purchase_limit,
    		'term_id'			=> $this->request->term_id,
    		'type'				=> $this->request->type,
            'access_request_info'   => json_encode($this->request->access_request_info) ?? null,
            'valid_domains'      => str_replace(["{", "}"], "", json_encode($this->request->valid_domains)) ?? [],
            'restriction'       => $this->request->restriction ? 1 : 0,
            'auto_approve'      => $this->request->auto_approve ? 1 :0,
            'category'          => $this->request->category,
            'top_up'            => $this->request->top_up ? 1 : 0,
            'top_up_amount'     => $this->request->top_up_amount,
            'stream_id'         => $this->request->stream_id,
            'stream_type'       => $this->request->stream_type,
            'rtmp_url'          => $this->request->rtmp_url,
            'stream_response'   => $this->request->stream_response,
            'store_id'          => session()->get('store_id'),
    		'modified_by'		=> auth()->user()->id,
    	]);
    }

    public function inheritToPosting()
    {
        $postings = Posting::where('postings.event_id', $this->event->event_id)
                        ->get();

        if($postings) {
            foreach($postings as $posting) {
                $posting->update([
                    'term_id'           => $this->request->term_id,
                    'starting_time'     => Carbon::parse($this->request->starting_time)->toDateTimeString(),
                    'type'              => $this->request->type,
                    'modified_by'       => auth()->user()->id,
                ]);

                SearchablePosting::where('posting_id', $posting->posting_id)->searchable();
            }
        }

        return $this;
    }

    private function generateSlug()
    {
        $slug = Str::kebab(strtolower($this->request->event_name)).substr(0, 40);

        // $valid_update = false;
        // $event_slug_exists = Event::where('slug', $slug)->first();

        // if($this->event)
        //     $valid_update = $this->event->event_id == $event_slug_exists->event_id;

        // if($event_slug_exists && !$valid_update)
        //     $slug = $slug.'-'. uniqid(5);

        return $slug;
    }


     public function createEmailTemplate()
    {

         $this->email_template_declined = EventAccessRequestTemplate::create([
                                                'event_id'     => $this->event->event_id,
                                                'title'        => $this->request->event_name,
                                                'body'         => "<p><em style=\"color: rgb(70, 75, 93);\">&nbsp;Your registration to join the private event has not been approved due or may of the following criteria;</em></p><p><br></p><p><em style=\"color: rgb(70, 75, 93);\">For further assistance please call our Customer Service at 09178758657 / 09176224778</em></p>",
                                                'type'         => "Declined",
                                                'created_by'   => auth()->id(),
                                                'modified_by'  => auth()->id(),
                                            ]);


    	$this->email_template_approved = EventAccessRequestTemplate::create([
                                                'event_id'     => $this->event->event_id,
                                                'title'        => $this->request->event_name,
                                                'body'         =>"<p><em style=\"color: rgb(70, 75, 93);\">Your registration to join the private Event has been approved.</em></p><p><br></p><p>&nbsp;For further assistance please call our Customer Service at <em style=\"color: rgb(70, 75, 93);\">&nbsp;09178758657 / 09176224778</em></p>",
                                                'type'         => "Approved",
                                                'created_by'   => auth()->id(),
                                                'modified_by'  => auth()->id(),
                                            ]);

    	return $this;
    }

    public function createStream()
    {

        if($this->event->category == 'Live Selling' && !$this->event->stream_id){

            $client_secret = env('AMS_JWT_SECRET');

            // Create token header as a JSON string
            $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
    
            // Create token payload as a JSON string
            $payload = json_encode(new \stdClass());
    
            // Encode Header to Base64Url String
            $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
    
            // Encode Payload to Base64Url String
            $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
    
            // Create Signature Hash
            $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, $client_secret, true);
    
            // Encode Signature to Base64Url String
            $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
    
            // Create JWT
            $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;
    
            $form = [
                'headers' => [
                    'Authorization' => $jwt,
                ],
                'json' => [
                    'name'=> $this->event->name,
                    'publish'=> false,
                    'type'=> "liveStream",
                    'publishType'=> "WebRTC"
                ],
            ];

            $request = new GuzzleRequest($form);

            $response = $request->post(env('AMS_REST_URL').'/v2/broadcasts/create?autoStart=false');

        
            if($response->getStatusCode() == 500)
                abort(403, 'Something went wrong. Please try again.');

            if($response->getStatusCode() != 200 && $response->getStatusCode() != 500)
                abort($response->getStatusCode(), json_encode($request->data()));

            $stream = $response->original;

            $this->event->update([
                'stream_id'		  => $stream['streamId'],
                'stream_type'     => 'Public',
                'rtmp_url'		  => $stream['rtmpURL'],
                'stream_response' => json_encode($stream),
            ]);

        }
    }
}
