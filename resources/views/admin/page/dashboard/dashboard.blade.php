@extends('admin.layouts.admin-layout')

@section('title', 'Trang chủ')

@section('content')
    <h1 class="h3 mb-4">Chào mừng đến với Admin Dashboard</h1>

    <div class="row">
        <div class="col-md-4">
            <div class="card text-white bg-primary mb-3">
                <div class="card-body">
                    <h5 class="card-title">Đơn hàng</h5>
                    <p class="card-text">{{ $order_count }}</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-success mb-3">
                <div class="card-body">
                    <h5 class="card-title">Doanh thu</h5>
                    <p class="card-text">{{ number_format($totalRevenue, 0, ',', '.') }} đ</p>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card text-white bg-warning mb-3">
                <div class="card-body">
                    <h5 class="card-title">Số bàn</h5>
                    <p class="card-text">{{ $table_count }}</p>
                </div>
            </div>
        </div>
    </div>

    <h2 class="h5 mt-5">Danh sách đơn hàng gần đây</h2>
    <div class="table-responsive">
        <table class="table table-bordered table-hover align-middle mt-3">
            <thead class="table-light">
            <tr>
                <th>STT</th>
                <th>Mã đơn</th>
                <th>Bàn</th>
                <th>Ngày đặt</th>
                <th>Tổng tiền</th>
                <th>Trạng thái</th>
            </tr>
            </thead>
            <tbody>
            @forelse($orders as $index => $order)
                <tr>
                    <td>{{ $index + 1 }}</td>
                    <td>{{ $order->id }}</td>
                    <td>{{ $order->table_id }}</td>
                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                    <td>{{ number_format($order->total_price, 0, ',', '.') }} đ</td>
                    <td>
                        <span class="badge bg-{{ $order->status === 'done' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                            {{ ucfirst($order->status) }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="6" class="text-center text-muted">Không có đơn hàng nào.</td>
                </tr>
            @endforelse
            </tbody>
        </table>
    </div>
@endsection
