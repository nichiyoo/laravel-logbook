<?php

namespace App\Traits;

use App\Models\User;
use App\Enums\RoleType;
use App\Policies\UserPolicy;
use Illuminate\Auth\Access\Response;

trait HasBulkDeletePolicy
{
  /**
   * Determine whether the user can permanently delete the model.
   */
  public function deleteAny(User $user): bool
  {
    return $user->role === RoleType::Admin;
  }
}
