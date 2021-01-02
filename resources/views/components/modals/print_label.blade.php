<script type="text/javascript">
    function onExecutePrint() {
        /*printLabels.forEach(function (label) {
            $('#print_label_form input[name=order_id]').prop('value', label.order_id)
            $('#print_label_form input[name=fs_id]').prop('value', label.fs_id)
            $('#print_label_form').submit()
        })*/

        $('#print_label_form input[name=order_data]').prop('value', JSON.stringify(printLabels))
        $('#print_label_form').submit()
    }
</script>

<!-- Modal -->
<div class="modal fade" id="printLabelOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin ingin menerima <b id="accept_amount"></b> order</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                Print akan muncul di halaman baru

                <form 
                    id="print_label_form" 
                    method="GET" 
                    action="{{ route('dashboard.orders.print_shipping_label') }}" 
                    target="_blank">
                    @csrf
                    <!-- <input type="hidden" name="order_id">
                    <input type="hidden" name="fs_id"> -->
                    <input type="hidden" name="order_data">
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="onExecutePrint()" type="button" class="btn btn-primary">
                    <i class="fas fa-print"></i> Cetak Label
                </button>
            </div>
        </div>
    </div>
</div>