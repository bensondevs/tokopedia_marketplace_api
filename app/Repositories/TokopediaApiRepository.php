<?php

namespace App\Repositories;

use Illuminate\Support\Facades\DB;
use \Illuminate\Database\QueryException;
use \Illuminate\Support\Facades\Http;
use \Webpatser\Uuid\Uuid;
use DNS1D;

use App\Models\Shop;
use App\Models\Order;
use App\Models\OrderProduct;
use App\Models\Customer;
use App\Models\CourierLogo;
use App\Models\MarketplaceLogo;

class TokopediaApiRepository
{
    protected $clientId;
    protected $clientSecret;

    protected $authToken;

    public function __construct()
    {
        $this->clientId = env('TOKOPEDIA_CLIENT_ID');
        $this->clientSecret = env('TOKOPEDIA_CLIENT_SECRET');

        $this->authToken = $this->getAccessToken();
    }

    public function saveNewCustomersData($orders)
    {
        $newCustomers = [];
        foreach ($orders as $order) {
            // From Decryption
            $decrypted = $order['decryption'];
            $buyer = $decrypted['buyer'];
            $recipient = $decrypted['recipient'];

            // From direct Order JSON
            $address = $order['recipient']['address'];

            $newCustomer = [
                'invoice_num' => $order['invoice_ref_num'],
                'order_id' => $order['order_id'],
                'fs_id' => $order['fs_id'],
                'customer_account_name' => $buyer->name,
                'recipient_name' => $recipient->name,
                'recipient_address' => $recipient->address->address_full,
                'recipient_district' => $address['district'],
                'recipient_city' => $address['city'],
                'recipient_province' => $address['province'],
                'recipient_phone' => $recipient->phone,
            ];

            // Check if exist
            $invoiceNum = $order['invoice_ref_num'];
            $exist = DB::table('customers')
                ->where('invoice_num', $invoiceNum)
                ->count();

            // If not exist then save it to DB
            if (! $exist) {
                array_push($newCustomers, $newCustomer);
            }
        }

        return Customer::insert($newCustomers);
    }

    public function saveOrdersData($orders)
    {
        foreach ($orders as $order) {
            // From Decryption
            $decrypted = $order['decryption'];
            $buyer = $decrypted['buyer'];
            $recipient = $decrypted['recipient'];

            // From direct Order JSON
            $address = $order['recipient']['address'];

            // Marketplace Logo
            $marketplaceName = strtolower($order['marketplace']);
            $marketplaceLogo = MarketplaceLogo::where('marketplace_name', $marketplaceName)
                ->first();
            $marketplaceLogo = $marketplaceLogo ?
                $marketplaceLogo->logo : 'https://ecs7.tokopedia.net/img/logo-tokopedia-32.png';

            // Courier Logo
            $courierName = strtolower($order['logistics']['shipping_agency']);
            $courierLogo = CourierLogo::where('courier_name', $courierName)
                ->first();
            $courierLogo = $courierLogo ?
                $courierLogo->logo : 'https://ecs7.tokopedia.net/img/kurir-sicepat.png';

            // Prepare Products
            $products = $order['products'];

            // Prepare Order Total Weight and Price
            $totalWeight = (double) 0;
            $totalPrice = (double) 0;
            foreach ($products as $product) {
                // Add total weight per product item
                $totalWeight += $product['total_weight'];

                // Add total price per product
                $totalPrice += $product['total_price'];
            }

            // Prepare Shipping Price
            $shippingPrice = $order['amt']['shipping_cost'];
            $totalShippingPriceDiscount = $order['promo_order_detail']['total_discount_shipping'];
            $totalShippingPrice = $shippingPrice - $totalShippingPriceDiscount;

            $newOrder = [
                'marketplace' => $order['marketplace'],
                'marketplace_logo' => $marketplaceLogo,
                'courier_logo' => $courierLogo,
                'invoice_num' => $order['invoice_ref_num'],
                'order_id' => $order['order_id'],
                'recipient_name' => $recipient->name,
                'shop_name' => $order['shop_info']['shop_name'],
                'address' => $recipient->address->address_full,
                'weight' => $totalWeight,
                'total' => $totalPrice,
                'shipping_price' => $totalShippingPrice,
                'city' => $address['city'],
                'province' => $address['province'],
                'phone' => $recipient->phone,

                // Unknown yet
                'invoice_note' => '-',
            ];

            // Check if exist
            $invoiceNum = $order['invoice_ref_num'];
            $exist = DB::table('orders')
                ->where('invoice_num', $invoiceNum)
                ->count();

            // If not exist then save it to DB
            if (! $exist) {
                $savedOrder = new Order($newOrder);
                $savedOrder->id = Uuid::generate()->string;
                $savedOrder->save();

                // Save Order Product
                $savedOrderProductData = [];
                foreach ($products as $product) {
                    // Product Size seperated By ,
                    $p = explode(', ', $product['name']);
                    if ($p) {
                        $productName = $p[0];
                        $productSize = $p[1];
                    } else {
                        $productName = $p;
                        $productSize = '-';
                    }

                    array_push($savedOrderProductData, [
                        'id' => Uuid::generate()->string,
                        'order_id' => $savedOrder->id,
                        'product_name' => $productName,
                        'sku' => $product['sku'],
                        'size' => $productSize,
                        'quantity' => $product['quantity'],
                        'notes' => isset($product['notes']) ? $product['notes'] : '-',
                    ]);
                }

                OrderProduct::insert($savedOrderProductData);
            }
        }

        return true;
    }

    public function sendRequest(
        $requestType, 
        $apiUrl, 
        $headers = null,
        $returnType = 'json'
    ) {
        $request = $headers ? Http::withHeaders($headers) : (new Http);
        $request = $request->$requestType($apiUrl);
        $response = $request->$returnType();

        return $response;
    }

    public function getAccessToken()
    {
    	$clientId = env('TOKOPEDIA_CLIENT_ID');
    	$clientSecret = env('TOKOPEDIA_CLIENT_SECRET');
    	$apiUrl = 'https://accounts.tokopedia.com/token?grant_type=client_credentials';

    	// Encode the Cliend ID and Secret, details: https://developer.tokopedia.com/openapi/guide/#/authentication
    	$encodedAuthKey = base64_encode($clientId . ':' . $clientSecret);
    	
    	$headers = [
    		'Authorization' => 'Basic ' . $encodedAuthKey,
    		'Content-Length' => '0',
    	];

        $response = $this->sendRequest('post', $apiUrl, $headers);

    	return $response['access_token'];
    }

    public function decrypt($encryptedSecret, $content)
    {
        // Decrypt secret key
        $decrypter = app_path() . '/Encryptions/decrypt-secret.sh';
        $privateKey = app_path() . '/Encryptions/private_key.pem';
        $shellCommand = $decrypter . ' ' . $privateKey . ' ' . $encryptedSecret;
        $decryptedSecretKey = shell_exec($shellCommand);
        
        // Decode Content
        $bcontent = base64_decode($content);
        $bnonce = substr($bcontent, strlen($bcontent) - 12, strlen($bcontent));
        $bcipher = substr($bcontent, 0, strlen($bcontent) - 12);    

        // Prepare Cypher Text
        $taglength = 16;
        $tag = substr($bcipher, strlen($bcipher) - $taglength, strlen($bcipher));
        $acipher = substr($bcipher, 0, strlen($bcipher) - $taglength);

        $result = openssl_decrypt(
            $acipher, 'aes-256-gcm', 
            $decryptedSecretKey, 
            OPENSSL_RAW_DATA, 
            $bnonce, 
            $tag
        );

        return $result;
    }

    public function authorization($type)
    {
        $authToken = ($this->authToken) ? $this->authToken : $this->getAccessToken();

        return $type . ' ' . $authToken;
    }

    public function getShops()
    {
        $addedShops = Shop::all();

        $foundShops = [];
        foreach ($addedShops as $shop) {
            $apiUrl = 'https://fs.tokopedia.net/v1/shop/fs/' . $shop->app_id . '/shop-info';
        
            // API Request
            $headers = ['Authorization' => $this->authorization('Bearer')];
            $response = $this->sendRequest('get', $apiUrl, $headers, 'json');   

            foreach ($response['data'] as $fsShop) {
                $addedShop = $fsShop;
                $addedShop['fs_id'] = $shop->app_id;
                array_push($foundShops, $addedShop);
            }
        }

    	return $foundShops;
    }

    public function getShopInfo($fsId, $findShopId = null)
    {
        $apiUrl = 'https://fs.tokopedia.net/v1/shop/fs/' . $fsId . '/shop-info';
        $apiUrl .= ($findShopId) ? ('?shop_id=' . $findShopId) : '';
        
        // API Request
        $headers = ['Authorization' => $this->authorization('Bearer')];
        $response = $this->sendRequest('get', $apiUrl, $headers);

        // Array of shops info
        $shopsInfo = $response['data'];

        return $shopsInfo;
    }

    public function getInvoiceDetail($fsId, $invoiceNum)
    {
        $apiUrl = 'https://fs.tokopedia.net/v2/fs/' . $fsId . '/order?invoice_num=' . $invoiceNum;

        // API Request
        $headers = ['Authorization' => $this->authorization('Bearer')];
        $response = $this->sendRequest('get', $apiUrl, $headers);

        return $response['data'];
    }

    public function getInvoiceBooking($fsId, $orderId)
    {
        $apiUrl = "https://fs.tokopedia.net/v1/fs/$fsId/fulfillment_order?order_id=$orderId";

        // API Request
        $headers = [
            'Authorization' => $this->authorization('Bearer'),
            'Content-Type' => 'application/json',
        ];
        $response = $this->sendRequest('get', $apiUrl, $headers);

        return $response['data']['order_data'][0]['booking_data'];
    }

    public function getLogisticInfo($fsId, $shopId)
    {
        $apiUrl = "https://fs.tokopedia.net/v2/logistic/fs/$fsId/info?shop_id=$shopId";
        // API Request
        $headers = ['Authorization' => $this->authorization('Bearer')];
        $response = $this->sendRequest('get', $apiUrl, $headers);

        return $response['data'];
    }

    public function filterOrders($orders, $filterType, $filterValue)
    {
        $filterResults = [];

        if ($filterType == 'print_status') {

        } else if ($filterType == 'shipping_agency') {
            foreach ($orders as $order)
                if ($order['logistics']['shipping_agency'] == $filterValue)
                    array_push($filterResults, $filterValue);
        } else if ($filterType == 'stage_name') {
            foreach ($orders as $order) 
                if ($order['order_status_name'] == $filterValue)
                    array_push($filterResults, $order);
        }

        return $filterResults;
    }

    public function getOrders()
    {
        // Get All Shops
        $shops = $this->getShops();

        $headers = ['Authorization' => $this->authorization('Bearer')];

        $fsOrders = [];
        foreach ($shops as $shop) {
            $now = carbon()->now()->endOfDay();

            $toDate = $now->copy()->timestamp;
            $fromDate = $now->subDays(3)->copy()->timestamp;

            $apiUrl = 'https://fs.tokopedia.net/v2/order/list?fs_id=' . $shop['fs_id'];
            $apiUrl .= '&shop_id=' . $shop['shop_id'];
            $apiUrl .= '&from_date=' . $fromDate; 
            $apiUrl .= '&to_date=' . $toDate;
            $apiUrl .= '&page=1&per_page=10000';

            $response = $this->sendRequest('get', $apiUrl, $headers);

            // Logistic information about shop
            $shopLogistics = $this->getLogisticInfo($shop['fs_id'], $shop['shop_id']);

            // Collect shop orders data
            $shopOrders = $response['data'];
            if ($shopOrders) {
                foreach ($shopOrders as $key => $shopOrder) {
                    // Prepare Marketplace Info
                    $shopOrder['marketplace'] = 'tokopedia';

                    // Prepare Shop Info
                    $shopOrder['fs_id'] = $shop['fs_id'];
                    $shopOrder['shop_info'] = $shop;

                    // Prepare Logistic Service Type
                    foreach ($shopLogistics as $logistic) {
                        $shipperName = $logistic['shipper_name'];
                        $orderShippingAgency = $shopOrder['logistics']['shipping_agency'];


                        if ($shipperName == $orderShippingAgency) {
                            $shopOrder['logistic_service_type'] = $logistic['services'][0]['type_name']; 
                            break;
                        }
                    }

                    // Prepare Invoice Detail
                    $invoiceDetail = $this->getInvoiceDetail($shop['fs_id'], $shopOrder['invoice_ref_num']);

                    // Prepare Invoice Deadline
                    $shipmentFulfillment = $invoiceDetail['shipment_fulfillment'];
                    $deadline = $shipmentFulfillment['confirm_shipping_deadline'];
                    $shopOrder['deadline_time'] = $deadline;
                    $shopOrder['deadline_diff'] = (carbon()->now() < $deadline) ? 
                        carbon()->now()
                            ->diff($deadline)
                            ->format('%D Hari %H Jam %I Menit') : 
                        'Overdue';

                    // Decript the secret and content
                    $encryptedData = $shopOrder['encryption'];
                    $encryptedSecret = $encryptedData['secret'];
                    $content = $encryptedData['content'];
                    $shopOrder['decryption'] = (array) json_decode($this->decrypt($encryptedSecret, $content));

                    // Order Status Name
                    $orderStatusCode = $shopOrder['order_status'];

                    // Order Booking Data
                    $shopOrder['booking_info'] = $this->getInvoiceBooking($shop['fs_id'], $shopOrder['order_id']);

                    // Delivery Status
                    if ($orderStatusCode <= 10)
                        $shopOrder['order_status_name'] = 'Batal';
                    elseif ($orderStatusCode >= 100 && $orderStatusCode < 200)
                        $shopOrder['order_status_name'] = 'Belum Bayar';
                    elseif ($orderStatusCode >= 200 && $orderStatusCode < 300)
                        $shopOrder['order_status_name'] = ($orderStatusCode === 220) ? 
                            'Lunas' : 'Menunggu Konfirmasi Pembayaran';
                    elseif ($orderStatusCode >= 400 && $orderStatusCode < 500)
                        $shopOrder['order_status_name'] = 'Siap Dikirim';
                    elseif ($orderStatusCode == 550)
                        $shopOrder['order_status_name'] = 'Pengembalian';
                    elseif ($orderStatusCode >= 600 && $orderStatusCode < 700)
                        $shopOrder['order_status_name'] = 'Dikirim';
                    elseif ($orderStatusCode >= 700 && $orderStatusCode < 800)
                        $shopOrder['order_status_name'] = 'Selesai';
                    else
                        $shopOrder['order_status_name'] = 'Belum Bayar';

                    // Push each order to FS Orders
                    array_push($fsOrders, $shopOrder);
                } 
            }
        }

        // Save New Orders to Customers Database
        // $this->saveNewCustomersData($fsOrders);

        // Save New Orders to Orders Database
        $this->saveOrdersData($fsOrders);

    	return $fsOrders;
    }

    public function acceptOrder($fsId, $orderId)
    {
        $apiUrl = "https://fs.tokopedia.net/v1/order/$orderId/fs/$fsId/ack";
        $headers = ['Authorization' => $this->authorization('Bearer')];

        $response = $this->sendRequest('post', $apiUrl, $headers);

        return [
            'status' => ($response['data']) ? $response['data'] : 'error',
            'message' => $response['header']['messages'],
        ];
    }

    public function rejectOrder($fsId, $orderId, $rejectInfo = [])
    {
        $apiUrl = "https://fs.tokopedia.net/v1/order/$orderId/fs/$fsId/nack";
        $headers = [
            'Authorization' => $this->authorization('Bearer'),
            'Content-Type' => 'application/json'
        ];

        $response = (Http::withHeaders($headers)->post($apiUrl, $rejectInfo))->json();

        return [
            'status' => ($response['data']) ? $response['data'] : 'error',
            'message' => $response['header']['messages'],
        ];
    }

    public function printShippingLabel($fsId, $orderId)
    {
        $apiUrl = "https://fs.tokopedia.net/v1/order/$orderId/fs/$fsId/shipping-label?printed=0";
        $headers = ['Authorization' => $this->authorization('Bearer')];

        $response = $this->sendRequest('get', $apiUrl, $headers, 'body');
        
        return $response;
    }

    public function requestPickUp($fsId, $pickUpData = [])
    {
        $apiUrl = "https://fs.tokopedia.net/inventory/v1/fs/$fsId/pick-up";
        $headers = [
            'Authorization' => $this->authorization('Bearer'),
            'Content-Type' => 'application/json'
        ];

        $response = (Http::withHeaders($headers)->post($apiUrl, $pickUpData))->json();

        return [
            'status' => ($response['data']) ? $response['data'] : 'error',
            'message' => $response['header']['messages'],
        ];
    }
}
