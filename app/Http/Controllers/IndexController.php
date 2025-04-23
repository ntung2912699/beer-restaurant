<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Repositories\Interfaces\CategoryRepositoryInterface;
use App\Repositories\Interfaces\ProductRepositoryInterface;
use App\Repositories\Interfaces\TablesRepositoryInterface;
use Exception;
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
            return view('client.menu.index', compact('categoryAll'));
        }
        catch (Exception $e) {
            Log::error('Lỗi mở màn hình chính: ' . $e->getMessage());
            return response()->json('Đã xảy ra lỗi hệ thống', 200);
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
}
