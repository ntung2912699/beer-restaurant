<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    protected $orderRepo;
    protected $orderItemRepo;

    public function __construct(OrderRepositoryInterface $orderRepo, OrderItemRepositoryInterface $orderItemRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
    }

    // Hiển thị danh sách đơn hàng
    public function index(Request $request)
    {
        $orders = $this->orderRepo->all();
        return response()->json($orders);
    }

    // Thống kê doanh thu theo ngày
    public function statisticByDate(Request $request, $date = null)
    {
        $date = $date ?: Carbon::today()->format('Y-m-d');
        $orders = $this->orderRepo->all()->where('created_at', 'like', "%$date%");

        $totalRevenue = $orders->sum('total_price');
        return response()->json([
            'date' => $date,
            'total_revenue' => $totalRevenue
        ]);
    }

    // Thống kê doanh thu theo tuần
    public function statisticByWeek(Request $request)
    {
        $weekStart = Carbon::now()->startOfWeek()->format('Y-m-d');
        $weekEnd = Carbon::now()->endOfWeek()->format('Y-m-d');
        $orders = $this->orderRepo->all()->whereBetween('created_at', [$weekStart, $weekEnd]);

        $totalRevenue = $orders->sum('total_price');
        return response()->json([
            'week_start' => $weekStart,
            'week_end' => $weekEnd,
            'total_revenue' => $totalRevenue
        ]);
    }

    // Thống kê doanh thu theo tháng
    public function statisticByMonth(Request $request)
    {
        $monthStart = Carbon::now()->startOfMonth()->format('Y-m-d');
        $monthEnd = Carbon::now()->endOfMonth()->format('Y-m-d');
        $orders = $this->orderRepo->all()->whereBetween('created_at', [$monthStart, $monthEnd]);

        $totalRevenue = $orders->sum('total_price');
        return response()->json([
            'month_start' => $monthStart,
            'month_end' => $monthEnd,
            'total_revenue' => $totalRevenue
        ]);
    }

    // Báo cáo lợi nhuận
    public function profitReport(Request $request)
    {
        $totalRevenue = $this->orderRepo->all()->sum('total_price');
        $totalCost = $this->calculateTotalCost(); // Hàm tính chi phí
        $profit = $totalRevenue - $totalCost;

        return response()->json([
            'total_revenue' => $totalRevenue,
            'total_cost' => $totalCost,
            'profit' => $profit
        ]);
    }

    // Tính tổng chi phí, có thể tính theo công thức hoặc nhập từ dữ liệu
    private function calculateTotalCost()
    {
        // Ví dụ tính chi phí theo các yếu tố như vật tư, sản xuất, nhân công...
        // Chưa có mô hình chi phí cụ thể, nên trả về 0 cho ví dụ.
        return 0; // Sẽ được thay đổi nếu có bảng chi phí
    }
}