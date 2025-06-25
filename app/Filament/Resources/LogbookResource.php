<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use App\Models\Logbook;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Forms\Form;
use App\Enums\StatusType;
use App\Models\Equipment;
use Filament\Tables\Table;
use App\Enums\EquipmentType;
use Filament\Resources\Resource;
use Illuminate\Support\Collection;
use App\Filament\Resources\LogbookResource\Pages;

class LogbookResource extends Resource
{
  protected static ?string $model = Logbook::class;
  protected static ?string $navigationIcon = 'heroicon-o-document-chart-bar';
  protected static ?int $navigationSort = 6;

  protected static ?array $dependant = [
    'equipment_id',
    'work_time',
    'delivery_time',
    'trailer_time',
    'work_note',
    'delivery_note',
    'internal_moving_note',
    'trailer_note',
  ];

  public static function getModelLabel(): string
  {
    return __('Logbooks');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Log Management');
  }

  public static function form(Form $form): Form
  {
    $today = now();

    return $form
      ->schema([
        Forms\Components\DatePicker::make('date')
          ->displayFormat('F j, Y')
          ->default($today)
          ->native(false)
          ->required(),
        Forms\Components\ToggleButtons::make('type')
          ->label('Equipment Type')
          ->options(EquipmentType::class)
          ->required()
          ->inline()
          ->live()
          ->afterStateUpdated(function (Set $set) {
            collect(self::$dependant)->each(fn($reset) => $set($reset, null));
          }),
        Forms\Components\Select::make('project_id')
          ->relationship('project', 'name')
          ->searchable()
          ->preload()
          ->createOptionForm([
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
          ]),
        Forms\Components\TextInput::make('operator')
          ->required(),
        Forms\Components\Textarea::make('location')
          ->columnSpanFull()
          ->maxLength(255)
          ->required(),
        Forms\Components\Select::make('shift_id')
          ->relationship('shift', 'label')
          ->searchable()
          ->preload()
          ->createOptionForm([
            Forms\Components\TextInput::make('label')
              ->required(),
            Forms\Components\Textarea::make('description')
              ->required(),
          ]),
        Forms\Components\Select::make('equipment_id')
          ->relationship('equipment', 'label')
          ->options(
            fn(Get $get): Collection => Equipment::query()
              ->where('type', $get('type'))
              ->get()
              ->map(fn(Equipment $equipment) => $equipment->label)
          )
          ->searchable()
          ->preload()
          ->required()
          ->visible(fn(Get $get): bool => $get('type') !== null)
          ->live(),
        Forms\Components\Section::make('Crane Details')
          ->description('Fill the details of the crane log')
          ->visible(fn(Get $get): bool => $get('type') === EquipmentType::CRANE->value)
          ->schema([
            Forms\Components\TextInput::make('work_time')->numeric(),
            Forms\Components\TextInput::make('delivery_time')->numeric(),
            Forms\Components\Textarea::make('work_note')->columnSpanFull(),
            Forms\Components\Textarea::make('delivery_note')->columnSpanFull(),
            Forms\Components\Textarea::make('internal_moving_note')->columnSpanFull(),
          ])->columns(2),
        Forms\Components\Section::make('Trailer Details')
          ->description('Fill the details of the trailer log')
          ->visible(fn(Get $get): bool => $get('type') === EquipmentType::TRAILER->value)
          ->schema([
            Forms\Components\TextInput::make('trailer_time')->numeric(),
            Forms\Components\Textarea::make('trailer_note')->columnSpanFull(),
          ])->columns(2),
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
        Tables\Columns\ImageColumn::make('equipment.image')
          ->label('Image')
          ->circular(),
        Tables\Columns\TextColumn::make('equipment.code')
          ->label('Code')
          ->searchable(),
        Tables\Columns\TextColumn::make('equipment.name')
          ->label('Name')
          ->searchable(),
        Tables\Columns\TextColumn::make('date')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('operator')
          ->searchable(),
        Tables\Columns\TextColumn::make('location')
          ->searchable(),
        Tables\Columns\TextColumn::make('shift.label')
          ->badge()
          ->sortable(),
        Tables\Columns\TextColumn::make('project.name')
          ->sortable(),
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
          Tables\Actions\DeleteBulkAction::make(),
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
      'index' => Pages\ListLogbooks::route('/'),
      'create' => Pages\CreateLogbook::route('/create'),
      'view' => Pages\ViewLogbook::route('/{record}'),
      'edit' => Pages\EditLogbook::route('/{record}/edit'),
    ];
  }
}
