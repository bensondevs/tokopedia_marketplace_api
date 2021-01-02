<script type="text/javascript">
    function onRequestSubmit() {
        $('#request_pickup_form input[name=request_data]').prop('value', JSON.stringify(requestPickUps))
        $('#request_pickup_form').submit()
    }
</script>

<div class="modal fade" id="requestPickUpModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin ingin meminta penjemputan <b id="request_amount"></b> barang?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bersiaplah karena kurir akan datang setelah ini!

                <form id="request_pickup_form" method="POST" action="{{ route('dashboard.orders.request_pickup') }}">
                    @csrf

                    <input type="hidden" name="request_data">
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="onRequestSubmit()" class="btn btn-primary">
                    <i class="fas fa-people-carry mr-1"></i> Request Pick Up
                </button>
            </div>
        </div>
    </div>
</div>