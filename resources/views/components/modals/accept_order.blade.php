<script type="text/javascript">
    function onAcceptSubmit() {
        $('#accept_form input[name=order_data]').prop('value', JSON.stringify(confirmations))
        $('#accept_form').submit()
    }
</script>

<!-- Modal -->
<div class="modal fade" id="acceptOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin ingin menerima <b id="accept_amount"></b> order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Bersiaplah untuk memproses barang!

                <form id="accept_form" method="POST" action="{{ route('dashboard.orders.accept_order') }}">
                    @csrf

                    <input type="hidden" name="order_data">
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="onAcceptSubmit()" type="button" class="btn btn-success">
                    <i class="fas fa-check-square mr-1"></i> Terima Pesanan
                </button>
            </div>
        </div>
    </div>
</div>