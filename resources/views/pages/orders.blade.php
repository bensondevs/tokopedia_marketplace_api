@extends('layouts.app', ['page_name' => 'Orders'])

@push('header_scripts')
	<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
<script type="text/javascript">
	// Variables Based on Stages
	var confirmations = []
	var deliveries = []
	var printLabels = []
	var requestPickUps = []

	function clearArray() {
		confirmations = []
		deliveries = []
		printLabels = []
		requestPickUps = []

		// Count
		count()
	}

	function count() {
		// Accept or Decline
		let confirmationCounter = confirmations.length
		let confirmationCounterText = confirmationCounter ?
			'(' + confirmationCounter + ')' : ''
		$('#accept_order_button').html('Terima Pesanan ' + confirmationCounterText)
		$('#accept_amount').html(confirmationCounterText)
		$('#accept_order_button').prop('disabled', confirmationCounter <= 0)

		$('#reject_order_button').html('Tolak Pesanan ' + confirmationCounterText)
		$('#reject_amount').html(confirmationCounterText)
		$('#reject_order_button').prop('disabled', confirmationCounter <= 0)

		// Delivery
		let deliveryCounter = deliveries.length
		let deliveryCounterText = deliveryCounter ?
			'(' + deliveryCounter + ')' : ''
		$('#set_delivery_button').html('Atur Pengiriman ' + deliveryCounterText)
		$('#set_delivery_button').prop('disabled', deliveryCounter <= 0)

		// Print Label
		let printLabelCounter = printLabels.length
		let printLabelCounterText = printLabelCounter ?
			'(' + printLabelCounter + ')' : ''
		$('#print_label_button').html('Cetak Label ' + printLabelCounterText)
		$('#print_label_button').prop('disabled', printLabelCounter <= 0)

		// Request Pick Up
		let requestPickupCounter = requestPickUps.length
		let requestPickupCounterText = requestPickUps ?
			'(' + requestPickupCounter + ')' : ''
		$('#request_pickup_button').html('Request Pick Up' + requestPickupCounterText)
		$('#request_amount').html(requestPickupCounterText)
		$('#request_pickup_button').prop('disabled', requestPickupCounter <= 0)
	}

	function pushToArray(checkedValue) {
	    let value = {
	    	'order_id': checkedValue['order_id'],
	    	'shop_id': checkedValue['shop_id'],
	    	'fs_id': checkedValue['fs_id'],
	    	'shipping_agency': checkedValue['shipping_agency'],
	    }
	    let stage = checkedValue['order_stage']
	    let logisticServiceType = checkedValue['logistic_service_type']

	    if (stage == 'Lunas') {
	    	confirmations.push(value)
	    	console.log('Masuk ke dalam array' + stage)
	    	console.log(confirmations)
	    } else if (stage == 'Siap Dikirim') {
	    	printLabels.push(value)
	    	console.log('Masuk ke dalam array' + stage)
	    	console.log(printLabels)

	    	if (logisticServiceType == 'pickup') {
	    		requestPickUps.push(value)
		    	console.log('Masuk ke dalam array request pickup')
		    	console.log(requestPickUps)
	    	}
	    }

	    // Count
	    count()
	}

	function findArrayIndex(array, orderId) {
		for (let index = 0; index < array.length; index++) {
			if (array[index]['order_id'] == orderId && array[index]['fs_id'])
				return index
		}
	}

	function pluckFromArray(uncheckedValue) {
		let order_id = uncheckedValue['order_id']
		let stage = uncheckedValue['order_stage']
		let logisticServiceType = uncheckedValue['logistic_service_type']

		if (stage == 'Lunas') {
			let arrayIndex = findArrayIndex(confirmations)
	    	confirmations.splice(arrayIndex, 1)
	    	console.log('Diambil dari array' + stage)
	    	console.log(confirmations)
	    } else if (stage == 'Siap Dikirim') {
	    	let arrayIndex = findArrayIndex(printLabels)
	    	printLabels.splice(arrayIndex, 1)
	    	console.log('Diambil dari array ' + stage)
	    	console.log(printLabels)

	    	if (logisticServiceType == 'pickup') {
	    		let arrayIndex = findArrayIndex(requestPickUps)
	    		requestPickUps.splice(arrayIndex, 1)
	    	}
	    }

	    // Count
	    count()

	    // Patch
	    $('#checkAll').prop('checked', false)
	}

	function checkAll() {
		let isChecked = $('#checkAll').is(':checked')

		// Clear array
		clearArray()

		$('.checkbox').prop('checked', isChecked)
		$('.checkbox:checkbox:checked').each(function() {
			let checkedValue = JSON.parse(this.value)
			console.log(checkedValue)
		    pushToArray(checkedValue)
		})
		$('.checkbox:checkbox:not(:checked)').each(function () {
			clearArray()
		})

		console.log('Massive check/uncheck done!')
	}
</script>


<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				@if ($errors->any())
	                <div class="alert alert-danger first">
	                    @foreach ($errors->all() as $error)
	                    {{ $error }} <br>
	                    @endforeach
	                </div>

	                <br />
	            @endif
	            @if(session()->get('success'))
	                <div class="alert alert-success">
	                    {{ session()->get('success') }}  
	                </div><br/>
	            @elseif(session()->get('error'))
	                <div class="alert alert-danger">
	                    {{ session()->get('error') }}
	                </div><br/>
	            @endif
				<div class="card">
				    <div class="card-header">
				        <h3 class="card-title">Orders</h3>
				    </div>
				    <!-- /.card-header -->
				    <div class="card-body">
				    	<div class="col-12">
						    <nav class="navbar navbar-expand navbar-white navbar-light">
						        <!-- Left navbar links -->
						        <ul class="navbar-nav">
						            <li class="nav-item d-none d-inline-block ">
						                <a href="{{ route('dashboard.orders') }}" class="nav-link">Semua</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Belum Bayar" class="nav-link">Belum Bayar</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Lunas" class="nav-link">Lunas</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Siap Dikirim" class="nav-link">Siap Dikirim</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Dikirim" class="nav-link">Dikirim</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Selesai" class="nav-link">Selesai</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Batal" class="nav-link">Batal</a>
						            </li>
						            <li class="nav-item d-none d-inline-block">
						                <a href="{{ route('dashboard.orders') }}?stage_name=Pengembalian" class="nav-link">Pengembalian</a>
						            </li>
						        </ul>
						    </nav>
						</div>

				        <table id="datatable" class="table table-bordered table-striped display">
				            <thead>
				                <tr>
				                	<th class="align-center align-middle">
				                		<input onclick="checkAll()" type="checkbox" value="" id="checkAll">
				                	</th>
				                    <th>No Pesanan</th>
				                    <th>Produk</th>
				                    <th>Pembeli</th>
				                    <th>Kurir</th>
				                    <th>Batas Kirim</th>
				                    <th>Status Cetak</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($orders as $order)
				                	<tr>
				                		<td class="align-center align-middle">
				                			<input class="checkbox" type="checkbox" name="order_check[]" value="{{ json_encode([
				                				'order_id' => $order['order_id'],
				                				'fs_id' => $order['fs_id'],
				                				'shop_id' => $order['shop_info']['shop_id'],
				                				'order_stage' => $order['order_status_name'],
				                				'shipping_agency' => $order['logistics']['shipping_agency'],
				                				'logistic_service_type' => $order['logistic_service_type'],
				                			]) }}">
				                		</td>
					                    <td>
					                    	{{ $order['order_id'] }} 
					                    	<br />
					                    	{{ $order['shop_info']['shop_name'] }}
					                    	<br />
					                    	{{ $order['order_status_name'] }} 
					                    </td>
					                    <td>
					                    	@foreach ($order['products'] as $product)
					                    		<p>
					                    			{{ $product['name'] }} x{{ $product['quantity'] }}
					                    			<br />
					                    			{{ formatRupiah($product['total_price']) }}
					                    			<br />
					                    			{{ $product['notes'] }}
					                    		</p>
					                    	@endforeach
					                    </td>
					                    <td>
					                    	{{ $order['decryption']['buyer']->name }} 
					                    	<br>
					                    	{{ $order['decryption']['buyer']->phone }}</td>
					                    <td>
					                    	{{ $order['logistics']['shipping_agency'] }} {{ $order['logistics']['service_type'] }}
					                    	<br />
					                    	{{ ($order['booking_info']['booking_code']) ? $order['booking_info']['booking_code'] : '-' }}
					                    </td>
					                    <td>
					                    	{{ $order['deadline_diff'] }}
					                    	<!-- <br />
					                    	({{ $order['deadline_time'] }}) -->
					                    </td>
					                    <td>{{ ($order['order_status'] == 400) ? 'Label Pengiriman' : '-' }}</td>
					                </tr>
				                @endforeach
				            </tbody>
				            <tfoot>
				                <tr>
				                	<th></th>
				                    <th>No Pesanan</th>
				                    <th>Produk</th>
				                    <th>Pembeli</th>
				                    <th>Kurir</th>
				                    <th>Batas Kirim</th>
				                    <th>Status Cetak</th>
				                </tr>
				            </tfoot>
				        </table>
				    </div>
				    <!-- /.card-body -->
				</div>
			</div>
		</div>
	</div>
</section>

<script type="text/javascript">
	$('.checkbox').change(function () {
		let checked = this.checked
		let value = this.value
		var checkedValue = JSON.parse(value)
		console.log('Action done!')

		checked ?
			pushToArray(checkedValue) :
			pluckFromArray(checkedValue)
	})
</script>

@include('components.modals.request_pickup')
@include('components.footerbar')

@endsection

@push('footer_scripts')
<script>
  $(document).ready(function () {
    $('#datatable').DataTable({
	    columnDefs: [ {
            orderable: false,
            className: 'select-checkbox',
            targets:   0
        }],
        select: {
            style:    'os',
            selector: 'td:first-child'
        },
        order: [[1, 'asc']]
	});
});
</script>
@endpush