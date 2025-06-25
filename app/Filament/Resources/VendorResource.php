<?php

namespace App\Filament\Resources;

use App\Filament\Resources\VendorResource\Pages;
use App\Filament\Resources\VendorResource\RelationManagers;
use App\Models\Vendor;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class VendorResource extends Resource
{
  protected static ?string $model = Vendor::class;
  protected static ?string $navigationIcon = 'heroicon-o-building-office';
  protected static ?int $navigationSort = 4;

  public static function getModelLabel(): string
  {
    return __('Vendors');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Management');
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
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
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\ImageColumn::make('equipments.image')
          ->circular()
          ->stacked()
          ->limit(3),
        Tables\Columns\TextColumn::make('description')
          ->limit(50),
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
      RelationManagers\EquipmentsRelationManager::class,
    ];
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListVendors::route('/'),
      'view' => Pages\ViewVendor::route('/{record}'),
      'create' => Pages\CreateVendor::route('/create'),
      'edit' => Pages\EditVendor::route('/{record}/edit'),
    ];
  }
}
