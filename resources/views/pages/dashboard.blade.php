@extends('layouts.app', ['page_name' => 'Main Dashboard'])

@push('header_scripts')
	<link rel="stylesheet" href="/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css">
	<link rel="stylesheet" href="/plugins/datatables-responsive/css/responsive.bootstrap4.min.css">
@endpush

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-header">
				        <h3 class="card-title">Data Customer</h3>
				    </div>
				    <!-- /.card-header -->
				    <div class="card-body">
				        <table id="datatable" class="table table-bordered table-striped display">
				            <thead>
				                <tr>
				                	<th>Marketplace</th>
									<th>Nama Toko</th>
									<th>No Invoice</th>
									<th>Order ID</th>
									<th>Nama Penerima</th>
									<th>Alamat</th>
									<th>Berat</th>
									<th>Total</th>
									<th>Ongkir</th>
									<th>No Telp</th>
									<th>Catatan</th>
									<th>Action</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($orders as $order)
				                	<tr>
					                    <td>{{ $order->marketplace }}</td>
										<td>{{ $order->shop_name }}</td>
										<td>{{ $order->invoice_num }}</td>
										<td>{{ $order->order_id }}</td>
										<td>{{ $order->recipient_name }}</td>
										<td>{{ $order->address }}, {{ $order->city }}, {{ $order->province }}</td>
										<td>{{ $order->weight }}</td>
										<td>{{ formatRupiah($order->total) }}</td>
										<td>{{ formatRupiah($order->shipping_price) }}</td>
										<td>{{ $order->phone }}</td>
										<td>{{ $order->invoice_note }}</td>
					                    <td>
					                    	<a class="btn btn-primary" href="{{ route('dashboard.orders.load_shipping_label') }}">
					                    		Print
					                    	</a>
					                    </td>
					                </tr>
				                @endforeach
				            </tbody>
				            <tfoot>
				                <tr>
				                	<th>Marketplace</th>
									<th>Nama Toko</th>
									<th>No Invoice</th>
									<th>Order ID</th>
									<th>Nama Penerima</th>
									<th>Alamat</th>
									<th>Berat</th>
									<th>Total</th>
									<th>Ongkir</th>
									<th>No Telp</th>
									<th>Catatan</th>
									<th>Action</th>
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
@endsection

@push('footer_scripts')
<script>
$(function () {
    $('#datatable').DataTable({
		"paging": true,
		"lengthChange": false,
		"searching": true,
		"ordering": true,
		"info": true,
		"autoWidth": false,
		"responsive": true,
	});
});
</script>
@endpush