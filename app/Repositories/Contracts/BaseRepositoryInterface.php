<?php

namespace App\Repositories\Contracts;

interface BaseRepositoryInterface
{
    public function create(array $data);

    public function update($model, array $data);

    public function delete($id);

    public function find($id);

    public function all();
}
