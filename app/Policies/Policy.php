<?php

namespace App\Policies;

use App\Models\User;


class Policy
{

	public function before($user, $ability)
    {	
        return User::isDeveloper();
    }   

    public function getModel()
    {
    	return $this->model;
    }
}