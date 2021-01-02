<?php 
    $rejectReasons = [
        '[1] Product(s) out of stock',
        '[2] Product variant unavailable',
        '[3] Wrong price or weight',
        '[4] Shop closed.',
        '[5] Others',
        '[7] Courier problem (reason required)',
        '[8] Buyer’s request (reason required)',
    ];
?>

<script type="text/javascript">
    function onRejectSubmit() {
        $('#reject_form input[name=order_data]').prop('value', JSON.stringify(confirmations))
        $('#reject_form').submit()
    }
</script>

<!-- Modal -->
<div class="modal fade" id="rejectOrderModal" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalLabel">Apakah Anda Yakin ingin menolak <b id="reject_amount"></b> order?</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form id="reject_form" method="POST" action="{{ route('dashboard.orders.reject_order') }}">
                    @csrf

                    <input type="hidden" name="order_data">

                    <div class="form-group">
                        <label>Alasan Penolakan *</label>
                        <select class="form-control" name="reason_code" required>
                            @foreach ($rejectReasons as $key => $rejectReason)
                                <option value="{{ ($key + 1) }}">{{ $rejectReason }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Penjelasan Alasan *</label>
                        <input class="form-control" type="text" name="reason" placeholder="Sedang liburan" required>
                    </div>

                    <div class="form-group">
                        <label id="label_shop_close_end_date">Toko Tutup Sampai (opsional)</label>
                        <input class="form-control" type="date" name="shop_close_end_date">
                    </div>

                    <div class="form-group">
                        <label id="label_shop_close_note">Catatan Toko Tutup(opsional)</label>
                        <input class="form-control" type="text" placeholder="Tolong order kapan kapan" name="shop_close_note">
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button onclick="onRejectSubmit()" type="button" class="btn btn-danger">
                    <i class="fas fa-times-circle mr-1"></i> Tolak Pesanan
                </button>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">
    $('select[name=reason_code]').change(function () {
        console.log(this.value)

        $('input[name=shop_close_end_date]').prop('required', this.value == 4)
        $('input[name=shop_close_note]').prop('required', this.value == 4)

        let importantTag = ((this.value == 4) ? ' *' : ' (opsional)')
        $('#label_shop_close_end_date').text('Toko Tutup Sampai' + importantTag)
        $('#label_shop_close_note').text('Alasan Toko Tutup' + importantTag)
    })
</script>