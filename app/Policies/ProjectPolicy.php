<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleType;
use App\Models\Project;
use App\Traits\HasBulkDeletePolicy;

class ProjectPolicy
{
  use HasBulkDeletePolicy;

  /**
   * List of allowerd roles for the user.
   */
  public array $allowed = [
    RoleType::Admin,
    RoleType::Manager,
  ];

  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return in_array($user->role, $this->allowed);
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, Project $project): bool
  {
    return in_array($user->role, $this->allowed);
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
  public function update(User $user, Project $project): bool
  {
    return $user->role === RoleType::Admin;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, Project $project): bool
  {
    return $user->role === RoleType::Admin;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, Project $project): bool
  {
    return false;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, Project $project): bool
  {
    return false;
  }
}
