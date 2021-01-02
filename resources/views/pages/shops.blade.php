@extends('layouts.app', ['page_name' => 'Shops'])

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
				        <h3 class="card-title">Shops</h3>
				    </div>
				    <!-- /.card-header -->
				    <div class="card-body">
				        <table id="datatable" class="table table-bordered table-striped">
				            <thead>
				                <tr>
				                    <th>Shop ID</th>
				                    <th>User ID</th>
				                    <th>Shop Name</th>
				                    <th>Logo</th>
				                </tr>
				            </thead>
				            <tbody>
				                @foreach ($shops as $shop)
				                	<tr>
					                    <th>{{ $shop['shop_id'] }}</th>
					                    <th>{{ $shop['user_id'] }}</th>
					                    <th><a href="{{ $shop['shop_url'] }}" target="_blank">{{ $shop['shop_name'] }}</a></th>
					                    <th><img swidth="50" height="50" src="{{ $shop['logo'] }}"></th>
					                </tr>
				                @endforeach
				            </tbody>
				            <tfoot>
				                <tr>
				                    <th>Shop ID</th>
				                    <th>User ID</th>
				                    <th>Shop Name</th>
				                    <th>Logo</th>
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
<!-- DataTables -->
<script src="/plugins/datatables/jquery.dataTables.min.js"></script>
<script src="/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js"></script>
<script src="/plugins/datatables-responsive/js/dataTables.responsive.min.js"></script>
<script src="/plugins/datatables-responsive/js/responsive.bootstrap4.min.js"></script>
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