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
}

abstract class BaseRepository implements IBaseRepository
{

  protected array $defaultRelations = [];

  protected Model $model;

  public function __construct(Model $model, array $relations)
  {
    $this->model = $model;
    $this->defaultRelations = $relations;
  }

  public function getDefaultRelations(): array
  {
    return $this->defaultRelations;
  }

  public function getAll(): Collection
  {
    return $this->model->all();
  }

  public function findById(string $id): ?Model
  {
    return $this->model->find($id);
  }

  public function getAllWithPagination(int $perPage = 15): LengthAwarePaginator
  {
    return $this->model
      ->with($this->getDefaultRelations())
      ->latest()
      ->paginate($perPage);
  }

  public function create(array $data): Model
  {
    return $this->model->create($data);
  }

  public function update(string $id, array $data): ?Model
  {
    $dataToUpdate = $this->model->find($id);

    if ($dataToUpdate) {
      $dataToUpdate->update($data);
      return $dataToUpdate->fresh($this->getDefaultRelations());
    }

    return null;
  }

  public function delete(string $id): bool
  {
    $product = $this->model->find($id);

    if ($product) {
      return $product->delete();
    }

    return false;
  }

  public function deleteMany(array $ids): int
  {
    return $this->model->whereIn('id', $ids)->delete();
  }
}
