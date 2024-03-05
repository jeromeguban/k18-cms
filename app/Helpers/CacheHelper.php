<?php
namespace App\Helpers;

// use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Redis;

class CacheHelper
{
	public static function validateIfExist($identifier)
	{
		return Redis::get($identifier) ? true : null;
	}

	public function getCache($identifier, $to_array=false)
	{
		return json_decode(Redis::get($identifier), $to_array);
	}

	public function setModelCache(Model $model = null, $model_name = null, $model_attribute = null)
	{

		$identifier = $model->getTable()."_".$model->getKey();

		Redis::set( $identifier, json_encode($model->getAttributes()) );
	
	}

	public function setCollectionCache(Collection $collections, Model $model, $collection_name = null, $collection_attribute = null, $model_name = null, $model_attribute = null, $to_array=false)
	{

		$identifier = ($model_name ?? $model->getTable())."_".($model_attribute ? $model->getAttribute($model_attribute) : $model->getKey())."_".($collection_name ?? $collections->first()->getTable());

		$collections_array =  $this->map($collections, function($collection) {
			
			if($collection instanceof Model)	
            	return $collection->getAttributes();

            return $collection;
                
        });

			
		Redis::set( $identifier, json_encode($collections_array, $to_array) );

	}

	public function deleteCache($identifier)
	{
		Redis::del($identifier);
	}

	private function checkIfArray($identifier)
	{
		return is_array($this->getCache($identifier));
	}

	private function map($elements, $callback)
    {
        $data = [];
        foreach($elements as $element) {
            $data[] = $callback($element);
        }

        return $data;
    }


}