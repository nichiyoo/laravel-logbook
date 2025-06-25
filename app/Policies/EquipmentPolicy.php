<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleType;
use App\Models\Equipment;
use App\Traits\HasBulkDeletePolicy;

class EquipmentPolicy
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
  public function view(User $user, Equipment $equipment): bool
  {
    return true;
  }

  /**
   * Determine whether the user can create models.
   */
  public function create(User $user): bool
  {
    return $user->role === RoleType::Admin;
  }

  /**
   * Determine whether the user can update the model.
   */
  public function update(User $user, Equipment $equipment): bool
  {
    return $user->role === RoleType::Admin;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Equipment $equipment): bool
  {
    return $user->role === RoleType::Admin;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Equipment $equipment): bool
  {
    return false;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Equipment $equipment): bool
  {
    return false;
  }
}
