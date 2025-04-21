<?php

namespace App\Http\Controllers;

use App\Repositories\Interfaces\OrderRepositoryInterface;
use Illuminate\Http\Request;

class OrderController extends Controller
{
    protected $orderRepo;

    public function __construct(OrderRepositoryInterface $orderRepo)
    {
        $this->orderRepo = $orderRepo;
    }

    public function index()
    {
        return response()->json($this->orderRepo->all());
    }

    public function show($id)
    {
        return response()->json($this->orderRepo->find($id));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'user_id' => 'required|exists:users,id',
            'total_price' => 'required|numeric',
        ]);
        return response()->json($this->orderRepo->create($data), 201);
    }

    public function update(Request $request, $id)
    {
        $data = $request->validate([
            'user_id' => 'sometimes|exists:users,id',
            'total_price' => 'sometimes|numeric',
        ]);
        return response()->json($this->orderRepo->update($id, $data));
    }

    public function destroy($id)
    {
        $this->orderRepo->delete($id);
        return response()->json(null, 204);
    }
}
