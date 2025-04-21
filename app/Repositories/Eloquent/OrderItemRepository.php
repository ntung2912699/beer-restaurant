<?php

namespace App\Repositories\Eloquent;

use App\Models\OrderItem;
use App\Repositories\Interfaces\OrderItemRepositoryInterface;

class OrderItemRepository implements OrderItemRepositoryInterface
{
    protected $model;

    public function __construct(OrderItem $orderItem)
    {
        $this->model = $orderItem;
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

