<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Eloquent\CategoryRepository;
use App\Repositories\Eloquent\ProductRepository;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductManagerController extends Controller
{
    protected $productRepo;

    protected $categoryRepo;

    public function __construct(
        ProductRepository $productRepo,
        CategoryRepository $categoryRepo
    )
    {
        $this->productRepo = $productRepo;
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        $products = $this->productRepo->paginate(8);
        $categories = $this->categoryRepo->all();
        return view('admin.page.product.index', compact('products', 'categories'));
    }

    public function destroy($id)
    {
        $this->productRepo->delete($id);
        return response()->json(['message' => 'Đã xóa thành công']);
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $data = $request->only(['name', 'price', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();

            $destination = public_path('assets/images-upload');
            $file->move($destination, $filename);
            $data['image'] = 'assets/images-upload/' . $filename;
        }
        $this->productRepo->create($data);
        return response()->json(['message' => 'Thêm sản phẩm thành công']);
    }

    public function show($id)
    {
        $product = $this->productRepo->find($id);
        return response()->json($product);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'price' => 'required|numeric|min:0',
            'category_id' => 'required|exists:categories,id',
            'description' => 'nullable|string',
            'image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ]);

        $product = $this->productRepo->find($id);
        if (!$product) {
            return response()->json(['message' => 'Không tìm thấy sản phẩm'], 404);
        }

        $data = $request->only(['name', 'price', 'category_id', 'description']);

        if ($request->hasFile('image')) {
            $oldImagePath = public_path($product->image); // ex: public/assets/image/abc.jpg
            if ($product->image && file_exists($oldImagePath)) {
                unlink($oldImagePath);
            }

            $file = $request->file('image');
            $filename = time() . '_' . $file->getClientOriginalName();
            $destination = public_path('assets/images-upload');

            if (!file_exists($destination)) {
                mkdir($destination, 0755, true);
            }

            $file->move($destination, $filename);

            $data['image'] = 'assets/images-upload/' . $filename;
        }

        $product->update($data);

        return response()->json(['message' => 'Cập nhật sản phẩm thành công']);
    }
}
