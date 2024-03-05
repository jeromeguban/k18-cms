<?php

namespace App\ModelObjects;

use App\Models\User;
use App\Models\Store as StoreModel;


class Store
{
	protected $user;

	public function __construct(User $user)
	{
		$this->user = $user;
	}

	public function syncStore(array $stores)
	{
		$this->user->userStores()->delete();

		foreach($stores as $store) {
			$this->user->userStores()->create([
				'store_id'	=> is_array($store) ? $store['id'] : $store
			]);
		}
	}
}