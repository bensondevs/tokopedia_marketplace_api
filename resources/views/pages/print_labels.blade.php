<style type="text/css">
    .tg  {border-collapse:collapse;border-spacing:0;margin:0px auto;}
    .tg td{font-family:Arial, sans-serif;font-size:9px;padding:3px 3px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg th{font-family:Arial, sans-serif;font-size:9px;font-weight:normal;padding:3px 3px;border-style:solid;border-width:1px;overflow:hidden;word-break:normal;border-color:black;}
    .tg .tg-km2t{font-weight:bold;border-color:#ffffff;text-align:left;vertical-align:top}
    .tg .tg-zv4m{border-color:#ffffff;text-align:left;vertical-align:top}
    .tg .tg-8jgo{border-color:#ffffff;text-align:center;vertical-align:center}
    .tg .tg-ofj5{border-color:#ffffff;text-align:right;vertical-align:top}
    .tg .tg-aw21{font-weight:bold;border-color:#ffffff;text-align:center;vertical-align:top}
    .tg .tg-h25s{font-weight:bold;border-color:#ffffff;text-align:right;vertical-align:top}
    .tg-wrap{padding-top:5px}
    @media screen and (max-width: 767px) {.tg {width: auto !important;}.tg col {width: auto !important;}.tg-wrap {overflow-x: auto;-webkit-overflow-scrolling: touch;margin: auto 5px;}}
    .tr-1 {border-top:solid; border-left:solid;border-right:solid}
    .tr-2 {border-top:1.5pt dotted; border-left:solid;border-right:solid}
    .tr-3 {border-left:solid;border-right:solid}
    .tr-4 {border-left:solid;border-right:solid;border-bottom:solid}
    .tr-5 {border-bottom:1.5pt dotted}
    .tr-6 {border-bottom:solid;text-align:center;vertical-align:top}
</style>

@foreach ($printedOrders as $key => $printedOrder)
    <div class="tg-wrap">
        <table class="tg" style="undefined;table-layout: fixed; width: 9.6cm">
            <colgroup>
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
                <col style="width: 0.48cm">
            </colgroup>
            <tr class="tr-1">
                <td class="tg-zv4m" colspan="7" rowspan="2">
                    <img src="{{ $printedOrder->marketplace_logo }}" alt="Image" width="90" height="20">
                </td>
                <td class="tg-8jgo" colspan="6" rowspan="2">
                    <span style="font-weight:bold">[Layanan]</span>
                </td>
                <td class="tg-ofj5" colspan="7" rowspan="2">
                    <img src="{{ $printedOrder->courier_logo }}" alt="Image" width="90" height="20">
                </td>
            </tr>
            <tr>
            </tr>
            <tr class="tr-2">
                <td class="tg-aw21" colspan="20">{{ $printedOrder->invoice_num }}</td>
            </tr>
            <tr class="tr-2">
                <td class="tg-8jgo" colspan="20" rowspan="4">
                    <img src="data:image/jpeg;base64,{{ $printedOrder->invoice_num_barcode }}" alt="Image" width="300" height="50">
                    <br>
                    <span style="font-weight:bold">[No Resi / Kode Booking]</span>
                </td>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr>
            </tr>
            <tr class="tr-2">
                <td class="tg-km2t" colspan="4">Penerima :</td>
                <td class="tg-km2t" colspan="7">{{ $printedOrder->recipient_name }}</td>
                <td class="tg-km2t" colspan="2">Dari : </td>
                <td class="tg-km2t" colspan="7">{{ $printedOrder->shop_name }}</td>
            </tr>
            <tr class="tr-3">
                <td class="tg-zv4m" colspan="13" rowspan="3">{{ $printedOrder->address }}</td>
                <td class="tg-h25s" colspan="3">Berat :</td>
                <td class="tg-zv4m" colspan="4">{{ $printedOrder->weight }} Kg</td>
            </tr>
            <tr class="tr-3">
                <td class="tg-h25s" colspan="3">COD :</td>
                <td class="tg-zv4m" colspan="4">{{ formatRupiah($printedOrder->total) }}</td>
            </tr>
            <tr class="tr-3">
                <td class="tg-h25s" colspan="3">Ongkir :</td>
                <td class="tg-zv4m" colspan="4">{{ formatRupiah($printedOrder->shipping_price) }}</td>
            </tr>
            <tr class="tr-3">
                <td class="tg-zv4m" colspan="11">{{ $printedOrder->district }}, {{ $printedOrder->province }}</td>
                <td class="tr-6" colspan="9" rowspan="2"><img src="data:image/png;base64,{{ $printedOrder->order_id_barcode }}" width="130" height="25"><br><span style="font-weight:bold">No. pesanan: {{ $printedOrder->order_id }}</span></td>
            </tr>
            <tr class="tr-4">
                <td class="tg-zv4m" colspan="11">{{ $printedOrder->phone_number }}</td>
            </tr>
            <tr class="tr-5">
                <td class="tg-km2t">No</td>
                <td class="tg-km2t" colspan="11">Nama Produk</td>
                <td class="tg-km2t" colspan="3">SKU</td>
                <td class="tg-km2t" colspan="3">Variasi</td>
                <td class="tg-km2t" colspan="2">Qty</td>
            </tr>
            @foreach ($printedOrder->products as $productKey => $product)
                <tr class="tr-5">
                    <td class="tg-zv4m">{{ ($productKey + 1) }}</td>
                    <td class="tg-zv4m" colspan="11">
                        {{ $product->product_name }} <br /> {{ $product->notes }}
                    </td>
                    <td class="tg-zv4m" colspan="3">{{ $product->sku }}</td>
                    <td class="tg-zv4m" colspan="3">{{ $product->size }}</td>
                    <td class="tg-zv4m" colspan="2">{{ $product->quantity }}</td>
                </tr>
            @endforeach
            <tr>
                <td class="tg-km2t" colspan="3">Catatan :</td>
                <td class="tg-zv4m" colspan="12">{{ $printedOrder->invoice_note }}</td>
                <td class="tg-km2t" colspan="3">Total :</td>
                <td class="tg-zv4m" colspan="2">{{ $printedOrder->products->count('quantity') }}</td>
            </tr>
        </table>
    </div>

    @if (($key + 1) < count($printedOrders))
        <p style="page-break-before: always"></p>
    @endif
@endforeach