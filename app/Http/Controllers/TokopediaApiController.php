<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Repositories\TokopediaApiRepository;

use DNS1D;
use App\Models\Order;

use PDF;

class TokopediaApiController extends Controller
{
	protected $tokopediaApi;

	public function __construct(
		TokopediaApiRepository $tokopediaApiRepository
	) {
		$this->tokopediaApi = $tokopediaApiRepository;
	}

	public function getShops()
	{
		$shops = $this->tokopediaApi->getShops();

		return view('pages.shops', compact('shops'));
	}

	public function getOrders(Request $request)
	{
		// Populate All Orders Last 3 days
		$orders = $this->tokopediaApi->getOrders();

		// Filter By Stage
		$orders = ($request->stage_name) ?
			$this->tokopediaApi->filterOrders(
				$orders,
				'stage_name',
				$request->stage_name
			) :
			$orders;

		// Filter By Print Status
		$orders = ($request->print_status) ?
			$this->tokopediaApi->filterOrders(
				$orders, 
				'print_status', 
				$request->print_status
			) :
			$orders;

		// Filter By Shipping Agency
		$orders = ($request->shipping_agency) ?
			$this->tokopediaApi->filterOrders(
				$orders, 
				'shipping_agency', 
				$request->shipping_agency
			) :
			$orders;

		return view('pages.orders', compact('orders'));
	}

	public function acceptOrder(Request $request)
	{
		// Collect all accepted orders
		$acceptedOrders = json_decode($request->order_data);

		foreach ($acceptedOrders as $order) {
			$acceptance = $this->tokopediaApi->acceptOrder($order->fs_id, $order->order_id);

			if ($acceptance['status'] == 'error') break;
		}

		return redirect()->back()->with($acceptance['status'], $acceptance['message']);
	}

	public function rejectOrder(Request $request)
	{
		$request->validate([
			'reason_code' => ['required'],
			'reason' => ['required'],
		]);

		// If no rejection data sent, give this instead
		$rejection = [
			'status' => 'Error',
			'Message' => 'Tidak ada pesanan yang bisa ditolak',
		];

		// Information inputted by user about rejection
		$rejectionInfo = $request->except(['order_data', '_token']);
		$rejectionInfo['reason_code'] = (int) $rejectionInfo['reason_code']; 
		if (isset($rejectionInfo['shop_close_end_date'])) {
			$rejectionInfo['shop_close_end_date'] = carbon()->parse($rejectionInfo['shop_close_end_date'])->format('d/m/Y');
		}

		// All rejected orders data
		$rejectedOrders = json_decode($request->order_data);
		foreach ($rejectedOrders as $key => $order) {
			// Reject one by one
			$rejection = $this->tokopediaApi->rejectOrder(
				$order->fs_id,
				$order->order_id, 
				$rejectionInfo
			);

			if ($rejection['status'] == 'error') break;
		}

		return redirect()
			->back()
			->with(
				$rejection['status'], 
				$rejection['message']
			);
	}

	public function printShippingLabel(Request $request)
	{
		$printedHTML = '';

		$printedLabels = json_decode($request->order_data);
		$printedOrderIds = [];
		foreach ($printedLabels as $label) {
			array_push($printedOrderIds, $label->order_id);
		}

		$printedOrders = Order::whereIn('order_id', $printedOrderIds)->get();

		return view('pages.print_labels', compact('printedOrders'));
	}

	public function loadShippingLabel(Request $request)
	{
		$printedOrders = Order::where('order_id', $request->order_id)->get();

		return view('pages.print_labels', compact('printedOrders'));
	}

	public function requestPickUp(Request $request)
	{
		// Prepare input data
		$pickedupOrders = json_decode($request->request_data);
		foreach ($pickedupOrders as $order) {
			$requestPickUp = $this->tokopediaApi->requestPickUp($order->fs_id, [
				'shop_id' => $order->shop_id,
				'order_id' => $order->order_id,
			]);
		}

		return redirect()->back()->with($requestPickUp['status'], $requestPickUp['message']);
	}
}