<?php

namespace App\Http\Services;

use Spatie\Activitylog\Models\Activity;

class ActivityLogService
{
    protected $activity, $modules;

    public function __construct(Activity $activity)
    {
        $this->activity = $activity;
        $this->modules = config('activity-log-url');
    }

    public function getUrl()
    {
        $module = $this->getModuleByBaseName();
        return $module['url'];
    }

    private function getModuleByBaseName()
    {
        return collect($this->modules)->filter(function ($value, $key) {
            return $value['module'] == $this->getClassBaseName();
        })->first();
    }

    private function getClassBaseName()
    {
        return class_basename($this->activity->subject_type);
    }
}
