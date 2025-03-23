<?php

namespace App\Repositories;

interface ServidorEfetivoRepositoryInterface
{
    public function all();
    public function paginate(int $perPage = 10);
    public function findById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
    public function findByIdWithPessoa(int $id);
    public function updateMatricula($pesId, string $matricula);
}