<section class="content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <!-- Default box -->
                <div class="card">
                    <div class="card-header">
                        <h3 class="card-title">Actions</h3>
                        <div class="card-tools">
                            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                            <i class="fas fa-minus"></i></button>
                        </div>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="d-inline-block">
                                <div class="align-center">
                                    <button 
                                        id="accept_order_button" 
                                        class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#acceptOrderModal"
                                        disabled="true">Terima Pesanan</button>
                                    <button 
                                        id="reject_order_button" 
                                        class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#rejectOrderModal"
                                        disabled="true">Tolak Pesanan</button>
                                    <button 
                                        id="set_delivery_button" 
                                        class="btn btn-primary"
                                        disabled="true">Atur Pengiriman</button>
                                    <button 
                                        id="print_label_button" 
                                        class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#printLabelOrderModal"
                                        disabled="true">Cetak Label</button>
                                    <button 
                                        id="request_pickup_button" 
                                        class="btn btn-primary"
                                        data-toggle="modal"
                                        data-target="#requestPickUpModal"
                                        disabled="true">
                                        Request Pick Up
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <!-- /.card -->
            </div>
        </div>
    </div>
</section>

@include('components.modals.accept_order')
@include('components.modals.reject_order')
@include('components.modals.set_delivery')
@include('components.modals.print_label')