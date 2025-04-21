<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CartRepositoryInterface;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartRepo;

    public function __construct(CartRepositoryInterface $cartRepo)
    {
        $this->cartRepo = $cartRepo;
    }

    public function index()
    {
        return response()->json($this->cartRepo->all());
    }

    public function show($id)
    {
        return response()->json($this->cartRepo->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
        ]);
        return response()->json($this->cartRepo->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
        ]);
        return response()->json($this->cartRepo->update($id, $data));
    }

    public function destroy($id)
    {
        $this->cartRepo->delete($id);
        return response()->json(null, 204);
    }
}
