<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;
use Illuminate\Support\Arr;

class RequiredParameters implements Rule
{
    private $section_type;

    public function __construct($section_type)
    {
        $this->section_type = $section_type;
    }

    public function passes($attribute, $value)
    {
        if ($this->section_type == 'item-posting') {
            $categories = Arr::get($value, 'categories', []);
            $tags = Arr::get($value, 'tags', []);
            return (count($categories) > 0 || count($tags) > 0);
        }
        return true;
    }

    public function message()
    {
        return 'The categories or tags field is required.';
    }
}
