<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ShiftResource\Pages;
use App\Models\Shift;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ShiftResource extends Resource
{
  protected static ?string $model = Shift::class;
  protected static ?string $navigationIcon = 'heroicon-o-clock';
  protected static ?int $navigationSort = 2;

  public static function getModelLabel(): string
  {
    return __('Shifts');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Management');
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('label')
          ->required(),
        Forms\Components\Textarea::make('description')
          ->required()
          ->columnSpanFull(),
      ]);
  }

  public static function table(Table $table): Table
  {
    return $table
      ->columns([
        Tables\Columns\TextColumn::make('created_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('updated_at')
          ->dateTime()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('label')
          ->searchable(),
        Tables\Columns\TextColumn::make('description')
          ->limit(50)
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ViewAction::make(),
        Tables\Actions\EditAction::make(),
        Tables\Actions\DeleteAction::make(),
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make(),
        ]),
      ]);
  }

  public static function getRelations(): array
  {
    return [
      //
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListShifts::route('/'),
      'create' => Pages\CreateShift::route('/create'),
      'edit' => Pages\EditShift::route('/{record}/edit'),
    ];
  }
}
