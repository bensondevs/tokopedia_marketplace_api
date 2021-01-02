<?php

namespace App\Repositories;

use App\Models\Shop;
use \Illuminate\Database\QueryException;

class ShopRepository
{
	public $status;
	public $statusCode;
	public $message;
	public $queryError;

   	public function addShop($shopData)
   	{
   		try {
   			$shop = new Shop();
   			$shop->fill($shopData);
   			$shop->save();

   			$this->status = 'success';
   			$this->message = 'Sukses menambah toko baru!';
   		} catch (QueryException $qe) {
   			$this->status = 'error';
   			$this->message = 'Gagal menambah toko baru!';
   			$this->queryError = $qe->getMessage();
   		}

   		return ($this->status == 'success') ?
   			$shop : null;
   	}

   	public function deleteShop($shopId)
   	{
   		try {
   			$shop = Shop::find($shopId)->destroy();

   			$this->status = 'success';
   			$this->message = 'Sukses menghapus toko baru!';
   		} catch (QueryException $qe) {
   			$this->status = 'error';
   			$this->message = 'Gagal menghapus toko baru!';
   			$this->queryError = $qe->getMessage();
   		}

   		return ($this->status == 'success');
   	}
}
