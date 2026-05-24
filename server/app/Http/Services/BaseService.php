<?php

namespace App\Http\Services;

use App\Http\Repositories\BaseRepository;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

interface IBaseService
{
  // CRUD
  public function getAll(): Collection;
  public function getOneById(string $id): ?Model;
  public function create(array $data): Model;
  public function update(string $id, array $data): ?Model;
  public function delete(string $id): bool;
  public function deleteMany(array $ids): int;

  // Фильтрация + сортировка + пагинация
  public function getAllWithPagination(array $filters, array $withRelation = [], int $perPage = 15, string $sortBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator;

  // Только фильтрация
  public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator;

  // Только сортировка
  public function getSorted(string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator;
}

abstract class BaseService implements IBaseService
{
  protected $repo;
  public function __construct(BaseRepository $repo)
  {
    $this->repo = $repo;
  }

  /**
   * ========================================
   * CRUD
   * ========================================
   */

  public function getAll(): Collection
  {
    return $this->repo->getAll();
  }

  public function getOneById(string $id): ?Model
  {
    return $this->repo->findById($id);
  }

  public function create(array $data): Model
  {
    return $this->repo->create($data);
  }

  public function update(string $id, array $data): ?Model
  {
    return $this->repo->update($id, $data);
  }

  public function delete(string $id): bool
  {
    return $this->repo->delete($id);
  }

  public function deleteMany(array $ids): int
  {
    return $this->repo->deleteMany($ids);
  }

  /**
   * ========================================
   * ФИЛЬТРАЦИЯ + СОРТИРОВКА + ПАГИНАЦИЯ
   * ========================================
   */

  /**
   * Всё вместе: фильтры + сортировка + пагинация
   */
  public function getAllWithPagination(array $filters, array $withRelation = [], int $perPage = 15, string $sortBy = 'created_at', string $direction = 'desc'): LengthAwarePaginator
  {
    return $this->repo->getAllWithPagination($perPage);
  }

  /**
   * Только фильтрация (сортировка по умолчанию)
   */
  public function getFiltered(array $filters, int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getFilteredPaginated(
      filters: $filters,
      perPage: $perPage
    );
  }

  /**
   * Только сортировка (без фильтров)
   */
  public function getSorted(string $sortBy = 'created_at', string $direction = 'desc', int $perPage = 15): LengthAwarePaginator
  {
    return $this->repo->getSortedPaginated(
      sortBy: $sortBy,
      direction: $direction,
      perPage: $perPage
    );
  }
}
