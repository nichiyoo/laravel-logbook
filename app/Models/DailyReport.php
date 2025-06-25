<?php

namespace App\Models;

use App\Enums\RoleType;
use Illuminate\Support\Facades\Auth;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DailyReport extends Model
{
  /**
   * The attributes that are mass assignable
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'date',
    'report',
    'user_id',
    'shift_id'
  ];

  /**
   * Get the shift that owns the daily report.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function shift(): BelongsTo
  {
    return $this->belongsTo(Shift::class);
  }

  /**
   * Get the user that owns the daily report.
   *
   * @return Illuminate\Database\Eloquent\Relations\BelongsTo
   */
  public function user(): BelongsTo
  {
    return $this->belongsTo(User::class);
  }

  /**
   * Scope a query to only include reports for the authenticated user.
   *
   * @param  \Illuminate\Database\Eloquent\Builder  $query
   * @return \Illuminate\Database\Eloquent\Builder
   */
  public function scopeOwned(Builder $query): Builder
  {
    $allowed = [
      RoleType::Admin,
      RoleType::Manager,
    ];

    $user = Auth::user();
    $check = in_array($user->role, $allowed);
    if ($check) return $query;

    return $query->where('user_id', $user->id);
  }

  /**
   * Override the boot method to add the owned scope.
   *
   * @return void
   */
  protected static function booted(): void
  {
    static::addGlobalScope('owned', fn(Builder $builder) => $builder->owned());
    static::creating(fn($model) => $model->user_id = Auth::id());
  }
}
