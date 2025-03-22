<?php

namespace App\Repositories;

interface PessoaRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 10);
    public function findById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function findWithRelations($id, array $relations);
    public function delete($id);
}