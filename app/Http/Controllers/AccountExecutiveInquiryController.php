<?php

namespace App\Http\Controllers;

use App\Helpers\ModelDecrypter;
use App\Models\AccountExecutive;
use App\Models\PostingInquiry;
use Illuminate\Http\Request;

class AccountExecutiveInquiryController extends Controller
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
        $this->authorize('list', PostingInquiry::class);

        $account_executive = AccountExecutive::where('user_id', auth()->user()->id)->first();

        $query = PostingInquiry::selectedFields()
            ->leftJoinPosting()
            ->leftJoinAccountExecutive()
            ->where('account_executive_id', $account_executive->id)
            ->sortable()
            ->searchable();

        if (request()->type || request()->category || request()->order_type || request()->from || request()->to) {
            $query->whereQueryScopes();
        }

        $response = $this->response($query);

        (request()->action == 'pagination' ? collect($response->items()) : $response)->each(function ($posting_inquiry) {
            $posting_inquiry = (new ModelDecrypter)->decryptModel($posting_inquiry);
        });

        return $response;

    }
}
