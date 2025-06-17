<?php

namespace App\Enums;

use Filament\Support\Contracts\HasLabel;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasIcon;

enum StatusType: string implements HasColor, HasIcon, HasLabel
{
  case DRAFT = 'Draft';
  case ONGOING = 'Ongoing';
  case COMPLETED = 'Completed';

  public function getLabel(): ?string
  {
    return match ($this) {
      self::DRAFT => 'Draft',
      self::ONGOING => 'Ongoing',
      self::COMPLETED => 'Completed',
    };
  }

  public function getColor(): string | array | null
  {
    return match ($this) {
      self::DRAFT => 'gray',
      self::ONGOING => 'primary',
      self::COMPLETED => 'success',
    };
  }

  public function getIcon(): ?string
  {
    return match ($this) {
      self::DRAFT => 'heroicon-o-pencil',
      self::ONGOING => 'heroicon-o-clock',
      self::COMPLETED => 'heroicon-o-check',
    };
  }
}
