<?php

namespace App\Policies;

use App\Models\User;
use App\Enums\RoleType;
use App\Models\EfficiencyPlanning;
use App\Traits\HasBulkDeletePolicy;

class EfficiencyPlanningPolicy
{
  use HasBulkDeletePolicy;

  /**
   * List of allowerd roles for the user.
   */
  public array $allowed = [
    RoleType::ADMIN,
    RoleType::MANAGER,
  ];

  /**
   * Determine whether the user can view any models.
   */
  public function viewAny(User $user): bool
  {
    return in_array($user->role, $this->allowed);;
  }

  /**
   * Determine whether the user can view the model.
   */
  public function view(User $user, EfficiencyPlanning $efficiencyPlanning): bool
  {
    return in_array($user->role, $this->allowed);;
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
  public function update(User $user, EfficiencyPlanning $efficiencyPlanning): bool
  {
    return $user->role === RoleType::ADMIN;
  }

  /**
   * Determine whether the user can delete the model.
   */
  public function delete(User $user, EfficiencyPlanning $efficiencyPlanning): bool
  {
    return $user->role === RoleType::ADMIN;
  }

  /**
   * Determine whether the user can restore the model.
   */
  public function restore(User $user, EfficiencyPlanning $efficiencyPlanning): bool
  {
    return false;
  }

  /**
   * Determine whether the user can permanently delete the model.
   */
  public function forceDelete(User $user, EfficiencyPlanning $efficiencyPlanning): bool
  {
    return false;
  }
}
