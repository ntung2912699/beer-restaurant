<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoriesMangementController extends Controller
{
    // Lấy danh sách category
    public function index()
    {
        $categories = Category::all();
        return view('admin.page.category.index', compact('categories'));
    }

    // Lấy chi tiết 1 category (JSON)
    public function show($id)
    {
        $category = Category::findOrFail($id);
        return response()->json($category);
    }

    // Tạo mới category
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        Category::create($validated);

        return response()->json(['message' => 'Category created successfully']);
    }

    // Cập nhật category
    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'icon' => 'nullable|string|max:255',
            'description' => 'nullable|string',
        ]);

        $category->update($validated);

        return response()->json(['message' => 'Category updated successfully']);
    }

    // Xóa category
    public function destroy($id)
    {
        $category = Category::findOrFail($id);
        $category->delete();

        return response()->json(['message' => 'Category deleted successfully']);
    }
}
