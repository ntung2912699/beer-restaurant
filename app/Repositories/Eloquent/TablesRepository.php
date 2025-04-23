<?php

namespace App\Repositories\Eloquent;

use App\Models\Tables;
use App\Repositories\Interfaces\TablesRepositoryInterface;

class TablesRepository implements TablesRepositoryInterface
{
    protected $model;

    public function __construct(Tables $tables)
    {
        $this->model = $tables;
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
        $product = $this->model->findOrFail($id);
        $product->update($data);
        return $product;
    }

    public function delete($id)
    {
        return $this->model->destroy($id);
    }
}
