<?php

namespace App\Http\Controllers;
ini_set('memory_limit', '-1');
ini_set('max_execution_time', 600);


use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Queries\ProductCatalogResult;
use PDF;
use App\Models\Event;

class ProductCatalogPDFController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index()
    {
        $postings = ProductCatalogResult::query()
                    ->where('postings.event_id', request()->event_id)
                    ->get();

        $event = Event::find(request()->event_id);

        $data = [
            'event'	            => $event,
            'postings'		    => $postings,
        ];


        if(request()->type == 'with') {
            $pdf = PDF::loadView('pdf.product-catalog-image', $data);
        }

        if(request()->type == 'without') {
            $pdf = PDF::loadView('pdf.product-catalog', $data);
        }

        return $pdf->stream($event->event_name.Carbon::now().'.pdf');
    }
}
