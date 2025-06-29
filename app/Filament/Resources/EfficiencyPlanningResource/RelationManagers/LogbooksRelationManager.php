<?php

namespace App\Filament\Resources\EfficiencyPlanningResource\RelationManagers;

use Filament\Forms\Form;
use Filament\Tables\Table;
use App\Filament\Resources\LogbookResource;
use Filament\Resources\RelationManagers\RelationManager;

class LogbooksRelationManager extends RelationManager
{
  protected static string $relationship = 'logbooks';

  public function form(Form $form): Form
  {
    return LogbookResource::form($form);
  }

  public function table(Table $table): Table
  {
    return LogbookResource::table($table);
  }
}
