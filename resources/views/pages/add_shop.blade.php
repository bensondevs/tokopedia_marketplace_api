@extends('layouts.app', ['page_name' => 'Shops'])

@section('content')
<section class="content">
	<div class="container-fluid">
		<div class="row">
			<div class="col-12">
				<div class="card">
				    <div class="card-header">
				        <h3 class="card-title">Tambah Toko</h3>
				    </div>
				    <!-- /.card-header -->
				    <div class="card-body">
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
				    	
				        <form method="POST" action="{{ route('dashboard.shops.store_shop') }}">
		                    @csrf

		                    <div class="form-group">
		                        <label>APP ID / FS ID *</label>
		                        <input class="form-control" type="number" name="app_id" placeholder="APP ID" required>
		                    </div>

		                    <div class="form-group">
		                        <label id="label_shop_close_end_date">CLIENT ID *</label>
		                        <input class="form-control" type="text" name="client_id" placeholder="CLIENT ID" required>
		                    </div>

		                    <div class="form-group">
		                        <label id="label_shop_close_note">CLIENT SECRET *</label>
		                        <input class="form-control" type="text" placeholder="Client Secret" name="client_secret" required>
		                    </div>

		                    <button class="btn btn-success">
		                    	<i class="fas fa-save mr-1"></i>Tambahkan
		                    </button>
		                </form>
				    </div>
				    <!-- /.card-body -->
				</div>
			</div>
		</div>
	</div>
</section>
@endsection