<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\Product;
use App\Repositories\Eloquent\ProductRepository;
use App\Repositories\Interfaces\OrderRepositoryInterface;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Http\Request;
use Carbon\Carbon;

class OrderManagementController extends Controller
{
    protected $orderRepo;
    protected $orderItemRepo;
    protected $productRepo;

    public function __construct(
        OrderRepositoryInterface $orderRepo,
        OrderItemRepositoryInterface $orderItemRepo,
        ProductRepository  $productRepo)
    {
        $this->orderRepo = $orderRepo;
        $this->orderItemRepo = $orderItemRepo;
        $this->productRepo = $productRepo;
    }

    public function index(Request $request)
    {
        $orders = $this->orderRepo->paginate(10); // Mỗi trang hiển thị 10 đơn hàng
        $products = $this->productRepo->all();
        return view('admin.page.order.index', compact('orders', 'products'));
    }

    public function show($id)
    {
        $order = Order::with(['items.product'])->findOrFail($id);

        $data = [
            'id' => $order->id,
            'table_id' => $order->table_id,
            'status' => $order->status,
            'items' => $order->items->map(function ($item) {
                return [
                    'product_id' => $item->product_id,
                    'product_name' => $item->product->name,
                    'quantity' => $item->quantity,
                ];
            }),
        ];

        return response()->json($data);
    }

    public function update(Request $request, $id)
    {
        $order = Order::with('items')->findOrFail($id);
        $quantities = $request->input('quantities', []);

        // Xóa toàn bộ món cũ
        $order->items()->delete();

        $total = 0;
        foreach ($quantities as $productId => $qty) {
            $product = Product::findOrFail($productId);
            $order->items()->create([
                'product_id' => $productId,
                'quantity' => $qty,
                'unit_price' => $product->price,
            ]);
            $total += $product->price * $qty;
        }

        $order->total_price = $total;
        $order->save();

        return response()->json(['message' => 'Cập nhật thành công']);
    }

    public function destroy($id)
    {
        $order = Order::findOrFail($id);
        $order->items()->delete(); // xóa các item trước
        $order->delete();

        return response()->json(['message' => 'Đã xóa đơn hàng']);
    }
}
