<?php

namespace App\Http\Repositories;

use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Database\Eloquent\Collection;

use App\Models\User;

class UserRepository extends BaseRepository
{
  protected array $defaultRelations = [''];

  public function __construct(User $model)
  {
    parent::__construct($model, self::$defaultRelations);
  }

  public function updatePassword(string $userId, string $newHashedPassword): ?User
  {
    $userToUpdate = $this->model->find($userId);
    if ($userToUpdate) {
      $userToUpdate->password_hash($newHashedPassword);
      $userToUpdate->save();
    }

    return $userToUpdate;
  }

  public function setDisableUser(string $userId): ?User
  {
    $userToUpdate = $this->model->find($userId);
    if ($userToUpdate) {
      $userToUpdate->isActive(false);
      $userToUpdate->save();
    }
    return $userToUpdate;
  }

  public function setActiveUser(string $userId): ?User
  {
    $userToUpdate = $this->model->find($userId);
    if ($userToUpdate) {
      $userToUpdate->isActive(true);
      $userToUpdate->save();
    }
    return $userToUpdate;
  }

  public function login(string $email, string $password): ?User
  {
    return $this->model
      ->where('email', $email)
      ->where('password', $password)
      ->first();
  }
}
