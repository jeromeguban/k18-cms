<?php

namespace App\Exports;

use Carbon\Carbon;
use Maatwebsite\Excel\Concerns\FromView;
use App\Helpers\ModelDecrypter;
use App\Models\PostingInquiry;
use Illuminate\Contracts\View\View;

class StoreInquiriesExport implements FromView
{
    public function view(): View
    {
        return view($this->getTemplate(), $this->getData());
    }

    public function getData()
    {
        $data = [];

        $query = PostingInquiry::selectedFields()
                            ->leftJoinPosting()
                            ->where('posting_inquiries.store_id', session()->get('store_id'))
                            ->whereBetween('posting_inquiries.created_at', [Carbon::parse(request()->from . '00:00:00')->toDateTimeString(), Carbon::parse(request()->to . '23:59:59')->toDateTimeString()])
                            ->sortable()
                            ->get();

        $data['items'] = $query;

        $data['items'] = (new ModelDecrypter)->decryptCollection($query);


        $data = [
            'items' => $query
        ];


        return $data;
    }

    public function getTemplate()
    {
        return 'excel.store-inquiry';
    }
}
