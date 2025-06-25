<?php

namespace App\Filament\Resources;

use App\Filament\Resources\EquipmentResource\Pages;
use App\Models\Equipment;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class EquipmentResource extends Resource
{
  protected static ?string $model = Equipment::class;
  protected static ?string $navigationIcon = 'heroicon-o-wrench';
  protected static ?int $navigationSort = 5;

  public static function getModelLabel(): string
  {
    return __('Equipment');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Management');
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\Select::make('vendor_id')
          ->relationship('vendor', 'name')
          ->searchable()
          ->preload()
          ->required()
          ->createOptionForm([
            Forms\Components\TextInput::make('name')
              ->required(),
            Forms\Components\Textarea::make('description')
              ->required()
              ->columnSpanFull(),
          ]),
        Forms\Components\TextInput::make('name')
          ->required(),
        Forms\Components\TextInput::make('code')
          ->nullable()
          ->unique(ignoreRecord: true),
        Forms\Components\Textarea::make('description')
          ->required()
          ->columnSpanFull(),
        Forms\Components\FileUpload::make('image')
          ->image()
          ->imageEditor()
          ->columnSpanFull()
          ->imageEditorMode(3),
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
        Tables\Columns\ImageColumn::make('image')
          ->circular(),
        Tables\Columns\TextColumn::make('name')
          ->searchable(),
        Tables\Columns\TextColumn::make('code')
          ->searchable(),
        Tables\Columns\TextColumn::make('vendor.name')
          ->numeric(),
        Tables\Columns\TextColumn::make('description')
          ->searchable(),
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
      'index' => Pages\ListEquipment::route('/'),
      'create' => Pages\CreateEquipment::route('/create'),
      'edit' => Pages\EditEquipment::route('/{record}/edit'),
    ];
  }
}
