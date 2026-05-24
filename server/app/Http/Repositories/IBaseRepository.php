<?php

namespace App\Http\Repositories;

use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseRepository
{
  public function getAll(): Collection;
  public function getAllWithPagination(int $perPage = 15): LengthAwarePaginator;
  public function findById(string $id): ?Model;
  public function create(array $data): Model;
  public function update(string $id, array $data): ?Model;
  public function delete(string $id): bool;
  public function deleteMany(array $ids): int;
  public function getFilteredPaginated(array $filters, int $perPage = 15): LengthAwarePaginator;
  public function getSortedPaginated(string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator;
}
