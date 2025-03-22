<?php

namespace App\Repositories;

interface UnidadeRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 10);
    public function findById($id);
    public function create(array $data);
    public function update(array $data);
    public function delete($id);
}