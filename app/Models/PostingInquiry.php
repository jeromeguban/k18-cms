<?php

namespace App\Models;

use App\Traits\Encryptable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;

class PostingInquiry extends Model
{
    use HasFactory, SoftDeletes, Encryptable;

    protected $searchable_fields = [
        'full_name', 'email',
    ];

    protected $primaryKey = "id";

    protected $table = "posting_inquiries";

    protected $encryptable = [
        'full_name',
        'mobile_no',
        'email',
        'company_name',
    ];

    protected $appends = [
        'tasks',
    ];

    public function scopeLeftJoinPosting($query)
    {
        $query->addSelect([
            'postings.description',
            'postings.slug',
        ]);

        $query->leftJoin('postings', 'postings.posting_id', '=', 'posting_inquiries.posting_id');

        return $query;
    }

    public function scopeLeftJoinAccountExecutive($query)
    {
        $query->addSelect([
            \DB::raw('CONCAT(account_executives.first_name," ",account_executives.last_name) as account_executive'),
            'account_executives.created_at as account_executives_created_date',
        ]);

        $query->leftJoin('account_executives', 'account_executives.id', '=', 'posting_inquiries.account_executive_id');

        return $query;
    }

    public function scopeWhereQueryScopes($query)
    {

        if (request()->type) {
            $query->where('posting_inquiries.type', request()->type);
        }

        if (request()->category) {
            $query->where('posting_inquiries.category', 'LIKE', '%' . request()->category . '%');
        }

        if (request()->status) {
            $query->where('posting_inquiries.status', request()->status);
        }

        // if (request()->order_type) {
        //     $query->orderBy('posting_inquiries.' . request()->filter_type, request()->order_type);
        // }

        // if (request()->from && request()->to) {
        //     $query->whereBetween(request()->filter_type ? 'posting_inquiries.' . request()->filter_type : 'posting_inquiries.created_at', [request()->from, request()->to]);
        // }
    }

    public function getTasksAttribute()
    {
        $tasks = PostingInquiryTask::selectedFields()
            ->where('posting_inquiry_tasks.posting_inquiry_id', $this->id)
            ->get();

        if (!$tasks) {
            return [
                'tasks' => [],
            ];
        }

        return $tasks;

    }
}
