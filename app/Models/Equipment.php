<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Uri;

class Equipment extends Model
{
  /**
   * The attributes that are mass assignable.
   *
   * @var array<string, mixed>
   */
  protected $fillable = [
    'name',
    'description',
    'image',
    'vendor_id',
  ];

  /**
   * Get the vendor that owns the equipment.
   */
  public function vendor(): BelongsTo
  {
    return $this->belongsTo(Vendor::class);
  }

  /**
   * Getter attribute for image.
   */
  public function image(): Attribute
  {
    $default = Uri::of('https://ui-avatars.com')
      ->withPath('api')
      ->withQuery([
        'name' => urlencode($this->name),
        'background' => '09090b',
        'color' => 'FFFFFF',
      ]);

    return Attribute::make(
      get: fn() => $this->attributes['image'] ?? (string) $default,
    );
  }
}
