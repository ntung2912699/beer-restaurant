<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CategoryRepositoryInterface;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    protected $categoryRepo;

    public function __construct(CategoryRepositoryInterface $categoryRepo)
    {
        $this->categoryRepo = $categoryRepo;
    }

    public function index()
    {
        return response()->json($this->categoryRepo->all());
    }

    public function show($id)
    {
        return response()->json($this->categoryRepo->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'name' => 'required|string|max:255',
        ]);
        return response()->json($this->categoryRepo->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'name' => 'sometimes|string|max:255',
        ]);
        return response()->json($this->categoryRepo->update($id, $data));
    }

    public function destroy($id)
    {
        $this->categoryRepo->delete($id);
        return response()->json(null, 204);
    }
}

