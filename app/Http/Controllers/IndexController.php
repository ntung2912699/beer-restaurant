<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Models\Cart;
use App\Models\CartItem;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\TablesRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class IndexController extends Controller
{
    protected $categoryRepo;

    protected $productRepo;

    protected $tableRepo;

    public function __construct(
        CategoryRepositoryInterface $categoryRepo,
        ProductRepositoryInterface $productRepo,
        TablesRepositoryInterface $tableRepo
    ) {
        $this->categoryRepo = $categoryRepo;
        $this->productRepo = $productRepo;
        $this->tableRepo = $tableRepo;
    }

    public function index() {
        try {
            $categoryAll = $this->categoryRepo->all();
            $tablesList = $this->tableRepo->all();
            return view('client.menu.index', compact('categoryAll','tablesList'));
        }
        catch (Exception $e) {
            Log::error('Lỗi mở màn hình chính: ' . $e->getMessage());
            return view('client.error.not-found');
        }
    }

    public function getProductsByCategory(Request $request)
    {
        try {
            $id = $request->get('categoryId');
            if (!$id) {
                return response()->json(['message' => 'Không tìm thấy id danh mục'], 404);
            }

            $category = $this->categoryRepo->find($id);
            if (!$category) {
                return response()->json(['message' => 'Danh mục không tồn tại'], 404);
            }

            $products = $category->products()->get();
            return response()->json($products, 200);
        } catch (\Exception $e) {
            Log::error('Lỗi lấy sản phẩm theo danh mục: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi hệ thống'], 500);
        }
    }

    public function searchProducts(Request $request) {
        try {
            $key = $request->get('key');
            $products = $this->productRepo->search($key);
            if (count($products) > 0) {
                return response()->json($products, 200);
            } else {
                return response()->json(['message' => 'Không tìm thấy sản phẩm tương ứng'], 200);
            }
        } catch (\Exception $e) {
            Log::error('Lỗi lấy khi tìm kiếm sản phẩm: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi tìm kiếm sản phẩm'], 500);
        }
    }

    public function getAllTables() {
      try {
        $tables = $this->tableRepo->all();
        return response()->json($tables, 200);
      } catch (Exception $e) {
        Log::error('Lỗi lấy khi lấy danh sách bàn: ' . $e->getMessage());
        return response()->json(['message' => 'Đã xảy ra lỗi khi lấy danh sách bàn'], 500);
      }
    }

    public function getCartByTable($tableId)
    {
        try {
            $cart = Cart::with('items.product')
                ->where('table_id', $tableId)
                ->latest()
                ->first();

            if (!$cart) {
                return response()->json([
                    'items' => [],
                    'total' => '0₫'
                ]);
            }

            $total = $cart->items->sum(function ($item) {
                return $item->quantity * $item->product->price;
            });

            return response()->json([
                'cart_id' => $cart->id,
                'items' => $cart->items,
                'total' => number_format($total, 0, ',', '.') . '₫'
            ]);
        } catch (Exception $e) {
            Log::error('Lỗi lấy khi lấy hóa đơn bàn: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi lấy hóa đơn bàn'], 500);
        }
    }

    public function saveCart(Request $request)
    {
        try {
            $request->validate([
                'table_id' => 'required|integer',
                'items' => 'required|array',
                'items.*.id' => 'required|integer',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            $existingCart = Cart::where('table_id', $request->table_id)->latest()->first();
            if ($existingCart) {
                $existingCart->items()->delete();
                $existingCart->delete();
            }

            $cart = Cart::create([
                'table_id' => $request->table_id,
            ]);

            foreach ($request->items as $item) {
                $cart->items()->create([
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                ]);
            }

            return response()->json(['message' => 'Đã lưu giỏ hàng thành công.']);
        } catch (Exception $e) {
            Log::error('Lỗi khi lưu giỏ hàng: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi lưu giỏ hàng'], 500);
        }
    }

    public function updateCart(Request $request, Cart $cart)
    {
        try {
            $request->validate([
                'table_id' => 'required|integer',
                'items' => 'required|array|min:1',
                'items.*.id' => 'required|integer|exists:products,id',
                'items.*.quantity' => 'required|integer|min:1',
            ]);

            // Cập nhật table_id nếu cần
            $cart->update(['table_id' => $request->table_id]);

            // Xóa item cũ
            $cart->items()->delete();

            // Tạo item mới
            foreach ($request->items as $item) {
                $cart->items()->create([
                    'product_id' => $item['id'],
                    'quantity' => $item['quantity'],
                ]);
            }

            return response()->json([
                'message' => 'Cart updated successfully.',
                'cart_id' => $cart->id
            ]);
        } catch (Exception $e) {
            Log::error('Lỗi khi cập nhật giỏ hàng: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi cập nhật giỏ hàng'], 500);
        }
    }

    public function orderStore(Request $request)
    {
        $request->validate([
            'table_id' => 'required|integer',
            'items' => 'required|array',
            'items.*.id' => 'required|integer',  // Kiểm tra id
            'items.*.quantity' => 'required|integer|min:1',  // Kiểm tra số lượng
        ]);

        DB::beginTransaction();

        try {
            // Tính tổng tiền từ các sản phẩm trong giỏ
            $totalPrice = 0;
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                $totalPrice += $product->price * $item['quantity'];
            }

            // Tạo đơn hàng mới
            $order = Order::create([
                'table_id'   => $request->table_id,
                'status'      => 'done',
                'total_price' => $totalPrice,
            ]);

            // Lưu chi tiết đơn hàng vào bảng OrderItems
            foreach ($request->items as $item) {
                $product = Product::find($item['id']);
                OrderItem::create([
                    'order_id'   => $order->id,
                    'product_id' => $item['id'],
                    'quantity'   => $item['quantity'],
                    'unit_price'      => $product->price,
                ]);
            }

            // Xóa giỏ hàng sau khi thanh toán (giả sử có bảng Cart để lưu giỏ hàng)
            Cart::where('table_id', $request->table_id)->delete();  // Xóa giỏ hàng của bàn

            DB::commit();

            return response()->json([
                'message' => 'Đã tạo đơn hàng thành công.',
                'order_id' => $order->id
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Lỗi tạo đơn hàng.', 'error' => $e->getMessage()], 500);
        }
    }

    public function cartDestroy($tableId)
    {
        try {
            $cart = Cart::where('table_id', $tableId)->latest()->first();

            if ($cart) {
                $cart->items()->delete();
                $cart->delete();
            }

            return response()->json(['message' => 'Xóa giỏ hàng thành công.']);
        }  catch (Exception $e) {
            Log::error('Lỗi khi xóa giỏ hàng: ' . $e->getMessage());
            return response()->json(['message' => 'Đã xảy ra lỗi khi xóa giỏ hàng'], 500);
        }
    }

    public function printContent($id)
    {
        $order = Order::with(['items.product', 'tables'])->findOrFail($id);
        // Thông tin thanh toán
        $bankId = "970422"; // Mã ngân hàng MB
        $accountNumber = "001099022228"; // Số tài khoản nhận tiền
        $amount = $order->total_price; // Số tiền VND
        $description = "Thanh toan don hang BEER BAO CAP" . $order->id; // Nội dung chuyển khoản
        $qrCode = $this->generateVietQR($bankId, $accountNumber, $amount, $description);
        return view('client.report.bill', compact('order', 'qrCode'));
    }

    protected function generateVietQR($bankId, $accountNumber, $amount, $description)
    {
        $client = new \GuzzleHttp\Client();

        $response = $client->post('https://api.vietqr.io/v2/generate', [
            'json' => [
                'accountNo'   => $accountNumber,
                'accountName' => 'NGUYEN VAN TUNG',
                'acqId'       => $bankId, // Mã ngân hàng MB: 970422
                'amount'      => number_format($amount, 0, '.', ''),
                'addInfo'     => $description,
                'format'      => 'image',
                'template'    => 'compact',
            ]
        ]);

        $body = json_decode($response->getBody(), true);
        return $body['data']['qrDataURL']; // đây là URL ảnh base64
    }

}
