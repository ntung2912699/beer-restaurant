<?php

namespace App\Repositories\Eloquent;

use App\Models\CartItem;
use App\Repositories\Interfaces\CartItemRepositoryInterface;

class CartItemRepository implements CartItemRepositoryInterface
{
    protected $model;

    public function __construct(CartItem $cartItem)
    {
        $this->model = $cartItem;
    }

    public function all()
    {
        return $this->model->all();
    }

    public function find($id)
    {
        return $this->model->findOrFail($id);
    }

    public function create(array $data)
    {
        return $this->model->create($data);
    }

    public function update($id, array $data)
    {
        $item = $this->model->findOrFail($id);
        $item->update($data);
        return $item;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
