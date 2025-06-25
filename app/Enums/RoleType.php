<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;

enum RoleType: string implements HasColor, HasIcon, HasLabel
{
  case ADMIN = 'Admin';
  case MANAGER = 'Manager';
  case FRONTMAN = 'Frontman';

  public function getLabel(): ?string
  {
    return match ($this) {
      self::ADMIN => 'Admin',
      self::MANAGER => 'Manager',
      self::FRONTMAN => 'Frontman',
    };
  }

  public function getColor(): string | array | null
  {
    return match ($this) {
      self::ADMIN => 'primary',
      self::MANAGER => 'success',
      self::FRONTMAN => 'gray',
    };
  }

  public function getIcon(): ?string
  {
    return match ($this) {
      self::ADMIN => 'heroicon-o-shield-check',
      self::MANAGER => 'heroicon-o-user-group',
      self::FRONTMAN => 'heroicon-o-user',
    };
  }
}
