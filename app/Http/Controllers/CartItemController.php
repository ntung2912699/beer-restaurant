<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\CartItemRepositoryInterface;
use Illuminate\Http\Request;

class CartItemController extends Controller
{
    protected $cartItemRepo;

    public function __construct(CartItemRepositoryInterface $cartItemRepo)
    {
        $this->cartItemRepo = $cartItemRepo;
    }

    public function index()
    {
        return response()->json($this->cartItemRepo->all());
    }

    public function show($id)
    {
        return response()->json($this->cartItemRepo->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'cart_id' => 'required|exists:carts,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);
        return response()->json($this->cartItemRepo->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'cart_id' => 'sometimes|exists:carts,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer',
        ]);
        return response()->json($this->cartItemRepo->update($id, $data));
    }

    public function destroy($id)
    {
        $this->cartItemRepo->delete($id);
        return response()->json(null, 204);
    }
}

