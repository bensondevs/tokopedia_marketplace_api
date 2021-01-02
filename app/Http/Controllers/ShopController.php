<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Repositories\ShopRepository;

class ShopController extends Controller
{
    protected $shop;

    public function __construct(ShopRepository $shopRepository)
    {
    	$this->shop = $shopRepository;
    }

    public function addShop()
    {
    	return view('pages.add_shop');
    }

    public function storeShop(Request $request)
    {
    	$shop = $this->shop->addShop($request->all());

    	return redirect()->back()->with($this->shop->status, $this->shop->message);
    }

    public function removeShop($id)
    {
    	$shop = $this->shop->deleteShop($id);

    	return redirect()->back()->with($this->shop->status, $this->shop->message);
    }
}
