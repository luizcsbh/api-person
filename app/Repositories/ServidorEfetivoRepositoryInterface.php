<?php

namespace App\Repositories;

interface ServidorEfetivoRepositoryInterface
{
    public function all();
    public function findById($id);
    public function create(array $data);
    public function update(array $data, $id);
    public function delete($id);
}