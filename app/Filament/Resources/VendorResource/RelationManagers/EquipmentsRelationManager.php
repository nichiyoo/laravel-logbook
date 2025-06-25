<?php

namespace App\Filament\Resources\VendorResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\EquipmentResource;
use Filament\Resources\RelationManagers\RelationManager;

class EquipmentsRelationManager extends RelationManager
{
  protected static string $relationship = 'equipments';

  public function form(Form $form): Form
  {
    return EquipmentResource::form($form);
  }

  public function table(Table $table): Table
  {
    return EquipmentResource::table($table);
  }
}
