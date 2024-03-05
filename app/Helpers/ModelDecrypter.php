<?php

namespace App\Helpers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Collection;


class ModelDecrypter 
{
    public function getEncryptable() {
	  	return $this->encryptable;
	}   
	
    public function decryptModel(Model $model)
    {
    	foreach ($model->getEncryptable() as $attribute) {
    		$model->setEncryptableAttribute($attribute, $model->getEncryptableAttribute($attribute));
    	}


    	return $model;
    }

    public function decryptCollection(Collection $collection)
    {

    	return $collection->map(function (Model $model) {
           
    		return $this->decryptModel($model);
    	});
    }
}
