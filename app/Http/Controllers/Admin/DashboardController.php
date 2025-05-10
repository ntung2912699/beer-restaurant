<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\TablesRepositoryInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    protected $orderRepo;

    protected $orderItemRepo;

    protected $tableRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        OrderItemRepositoryInterface $orderItemRepo,
        TablesRepositoryInterface $tableRepo
    )
    {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->tableRepo = $tableRepo;
    }

    public function index() {
        try {
            // Lấy ngày bắt đầu và kết thúc của tuần hiện tại
            $startOfWeek = Carbon::now()->startOfWeek(); // Mặc định là Thứ 2
            $endOfWeek = Carbon::now()->endOfWeek();     // Chủ nhật

            // Lấy đơn hàng trong tuần này
            $orders = $this->orderRepo->getOrdersInRange($startOfWeek, $endOfWeek);

            // Đếm số lượng bàn và đơn hàng (tùy bạn, có thể giữ hoặc bỏ)
            $table_count = count($this->tableRepo->all());
            $order_count = count($this->orderRepo->all());

            // Doanh thu của tuần này (chỉ tính đơn đã hoàn tất)
            $totalRevenue = Order::where('status', 'done')
                ->whereBetween('created_at', [$startOfWeek, $endOfWeek])
                ->sum('total_price');

            return view('admin.page.dashboard.dashboard', compact(
                'orders',
                'table_count',
                'order_count',
                'totalRevenue'
            ));
        } catch (\Exception $e) {
            return view('client.error.not-found');
        }
    }
}
