<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Helpers\ModelDecrypter;
use App\Models\AuctionAccessRequest;
use App\Exports\AuctionAccessRequestExport;

class AuctionAccessRequestController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware(['auth']);
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $this->authorize('list', AuctionAccessRequest::class);

        $query = AuctionAccessRequest::selectedFields()
                                ->joinCustomer()
                                ->joinCustomerLoginCredential()
                                ->joinAuction()
                                ->whereQueryScopes()
                                ->withRelations()
                                ->sortable()
                                ->searchable()
                                ->orderBy('auction_access_requests.id', 'DESC');

        if(request()->filter_customer == 'First Name')
            $query->where('customers.customer_lastname_index', md5(strtoupper(request()->search)));

        if(request()->filter_customer == 'Last Name')
            $query->where('customers.customer_firstname_index', md5(strtoupper(request()->search)));

        if(request()->filter_customer == 'Email')
            $query->where('customer_login_credentials.email_index', md5(request()->search));

        if(request()->filter_customer == 'Mobile No')
            $query->where('customer_login_credentials.mobile_no_index', md5(request()->search));

         $response = $this->response($query);

         (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function($auction_access_request) {
            $auction_access_request =   (new ModelDecrypter)->decryptModel($auction_access_request);
        });

       return $response;
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(AuctionAccessRequest $auction_access_request)
    {

        $this->authorize('delete', $auction_access_request);

        // return $auction_access_request;

        $auction_access_request->update([
            'deleted_by'   => auth()->id()
        ]);

        $auction_access_request->delete();

        activity()
            ->performedOn( $auction_access_request )
            ->withProperties($auction_access_request)
            ->log('Auction Customer Request has been deleted');

        return [
            'success' => 1
        ];

    }

    public function export()
    {
        if(!request()->auction_id)
            abort(403, "Please Select an Auction");

        return \Excel::download(new AuctionAccessRequestExport,
            'Auction Access Request List - '.now()->toDateTimeString().'.xlsx');
    }
}
