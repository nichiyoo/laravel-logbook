<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Project;
use Filament\Forms\Form;
use App\Enums\StatusType;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use App\Filament\Resources\ProjectResource\Pages;

class ProjectResource extends Resource
{
  protected static ?string $model = Project::class;
  protected static ?string $navigationIcon = 'heroicon-o-briefcase';
  protected static ?int $navigationSort = 4;

  public static function getModelLabel(): string
  {
    return __('Projects');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Log Management');
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\TextInput::make('name')
          ->required(),
        Forms\Components\ToggleButtons::make('status')
          ->options(StatusType::class)
          ->default(StatusType::DRAFT)
          ->inline()
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
        Tables\Columns\TextColumn::make('description')
          ->limit(50),
        Tables\Columns\TextColumn::make('status')
          ->badge()
          ->searchable(),
      ])
      ->filters([
        //
      ])
      ->actions([
        Tables\Actions\ActionGroup::make([
          Tables\Actions\ViewAction::make()->icon(null),
          Tables\Actions\EditAction::make()->icon(null),
          Tables\Actions\DeleteAction::make()->icon(null),
        ])->dropdown(true)
      ])
      ->bulkActions([
        Tables\Actions\BulkActionGroup::make([
          Tables\Actions\DeleteBulkAction::make()->icon(null),
        ]),
      ])
      ->recordAction(Tables\Actions\ViewAction::class);
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
      'index' => Pages\ListProjects::route('/'),
      'create' => Pages\CreateProject::route('/create'),
      'edit' => Pages\EditProject::route('/{record}/edit'),
    ];
  }
}
