<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum RoleType: string implements HasColor, HasIcon, HasLabel
{
  case Admin = 'Admin';
  case Manager = 'Manager';
  case Frontman = 'Frontman';

  public function getLabel(): ?string
  {
    return match ($this) {
      self::Admin => 'Admin',
      self::Manager => 'Manager',
      self::Frontman => 'Frontman',
    };
  }

  public function getColor(): string | array | null
  {
    return match ($this) {
      self::Admin => 'primary',
      self::Manager => 'success',
      self::Frontman => 'gray',
    };
  }

  public function getIcon(): ?string
  {
    return match ($this) {
      self::Admin => 'heroicon-o-shield-check',
      self::Manager => 'heroicon-o-user-group',
      self::Frontman => 'heroicon-o-user',
    };
  }
}
