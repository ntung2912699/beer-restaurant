<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\OrderItemRepositoryInterface;
use Illuminate\Http\Request;

class OrderItemController extends Controller
{
    protected $orderItemRepo;

    public function __construct(OrderItemRepositoryInterface $orderItemRepo)
    {
        $this->orderItemRepo = $orderItemRepo;
    }

    public function index()
    {
        return response()->json($this->orderItemRepo->all());
    }

    public function show($id)
    {
        return response()->json($this->orderItemRepo->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'order_id' => 'required|exists:orders,id',
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer',
        ]);
        return response()->json($this->orderItemRepo->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'order_id' => 'sometimes|exists:orders,id',
            'product_id' => 'sometimes|exists:products,id',
            'quantity' => 'sometimes|integer',
        ]);
        return response()->json($this->orderItemRepo->update($id, $data));
    }

    public function destroy($id)
    {
        $this->orderItemRepo->delete($id);
        return response()->json(null, 204);
    }
}
