<?php

namespace App\Enums;

use Filament\Support\Contracts\HasIcon;
use Filament\Support\Contracts\HasColor;
use Filament\Support\Contracts\HasLabel;

enum EquipmentType: string implements HasColor, HasIcon, HasLabel
{
  case CRANE = 'Crane';
  case TRAILER = 'Trailer';

  public function getLabel(): ?string
  {
    return match ($this) {
      self::CRANE => 'Crane',
      self::TRAILER => 'Trailer',
    };
  }

  public function getColor(): string | array | null
  {
    return match ($this) {
      self::CRANE => 'primary',
      self::TRAILER => 'success',
    };
  }

  public function getIcon(): ?string
  {
    return match ($this) {
      self::CRANE => 'heroicon-o-cog-6-tooth',
      self::TRAILER => 'heroicon-o-truck',
    };
  }
}
