<!-- resources/views/orders/print-content.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Hóa đơn</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 14px;
            width: 80mm;
            padding: 5px;
        }
        h3, h4 {
            text-align: center;
            margin: 0;
            font-size: 18px;
        }
        p {
            margin: 5px 0;
            font-size: 14px;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
            font-size: 14px;
        }
        th, td {
            border-bottom: 1px dashed #000;
            padding: 4px 0;
            text-align: left;
        }
        .text-right {
            text-align: right;
        }
        .total {
            font-weight: bold;
            font-size: 16px;
            margin-top: 10px;
        }
        .qr-code {
            text-align: center;
            margin-top: 15px;
        }
        .qr-code img {
            width: 120px;
            height: 120px;
        }
    </style>
</head>
<body>
    <h2 style="text-align: center; margin: 0">BIA BAO CẤP</h2>
    <h5 style="text-align: center;margin-top: 0px">ĐC: Số 2 - Ngõ 39 - Đường Vực Giang</h5>
    <h4>HÓA ĐƠN THANH TOÁN</h4>
    <p>Bàn: {{ $order->table_id ?? 'N/A' }}</p>
    <p>Mã đơn: #{{ $order->id }}</p>
    <p>Thời gian: {{ $order->created_at->format('d/m/Y H:i') }}</p>

    <table>
        <thead>
            <tr>
                <th>Sản phẩm</th>
                <th class="text-right">SL</th>
                <th class="text-right">Đơn Giá</th>
                <th class="text-right">Thành Tiền</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($order->items as $item)
                <tr>
                    <td>{{ $item->product->name }}</td>
                    <td class="text-right">{{ $item->quantity }}</td>
                    <td class="text-right">{{ number_format($item->unit_price) }}</td>
                    <td class="text-right">{{ number_format($item->unit_price * $item->quantity) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>

    <p class="total text-right">Tổng cộng: {{ number_format($order->total_price) }}₫</p>

    @if (!empty($qrCode))
        <div class="qr-code">
            <p>Quét mã để thanh toán</p>
            <img style="width:100%; height:100%" id="qr-image" src="{{ $qrCode }}" alt="QR Thanh toán">
        </div>
    @endif
</body>
</html>