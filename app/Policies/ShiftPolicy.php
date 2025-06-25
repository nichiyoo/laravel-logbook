<?php

namespace App\Policies;

use App\Models\User;
use App\Models\Shift;
use App\Enums\RoleType;
use App\Traits\HasBulkDeletePolicy;

class ShiftPolicy
{
  use HasBulkDeletePolicy;

  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return true;
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Shift $shift): bool
  {
    return true;
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->role === RoleType::ADMIN;
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Shift $shift): bool
  {
    return $user->role === RoleType::ADMIN;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Shift $shift): bool
  {
    return $user->role === RoleType::ADMIN;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Shift $shift): bool
  {
    return false;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Shift $shift): bool
  {
    return false;
  }
}
