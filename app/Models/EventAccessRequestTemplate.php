<?php

namespace App\Models;
use Illuminate\Database\Eloquent\SoftDeletes;

class EventAccessRequestTemplate extends Model
{
    use SoftDeletes;

    protected $table = "event_access_request_templates";

    protected $searchable_fields = [
        'events.event_name', 
        'event_access_request_templates.type', 
        'event_access_request_templates.body',
        'event_access_request_templates.title', 
    ];

    public function scopeJoinAuction($query)
    {
        $query->addSelect([
            'events.event_name',
            'events.slug'
        ]);

        $query->join('events', 'events.event_id', '=', 'event_access_request_templates.event_id');
    }


    // Relationships
    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function modifiedBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }
}
