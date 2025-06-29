<?php

namespace App\Filament\Resources;

use Carbon\Carbon;
use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Get;
use Filament\Forms\Set;
use Filament\Infolists;
use Filament\Forms\Form;
use App\Models\Equipment;
use Filament\Tables\Table;
use Filament\Resources\Resource;
use Filament\Infolists\Infolist;
use App\Models\EfficiencyPlanning;
use Illuminate\Database\Eloquent\Builder;
use App\Filament\Resources\EfficiencyPlanningResource\Pages;

class EfficiencyPlanningResource extends Resource
{
  protected static ?string $model = EfficiencyPlanning::class;
  protected static ?string $navigationIcon = 'heroicon-o-presentation-chart-bar';
  protected static ?int $navigationSort = 7;

  protected static ?array $plannings = [
    'planning_week_1',
    'planning_week_2',
    'planning_week_3',
    'planning_week_4',
  ];

  public static function getModelLabel(): string
  {
    return __('Efficiency Plannings');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Log Management');
  }

  public static function getEloquentQuery(): Builder
  {
    return parent::getEloquentQuery()->with('equipment', 'logbooks');
  }

  public static function form(Form $form): Form
  {
    $year = now()->year;
    $month = now()->month;

    $months = range(1, 12);
    $months = collect($months)->map(function ($month) {
      return Carbon::createFromFormat('m', $month)->format('F');
    });

    $plannings = array_map(
      fn($planning) => Forms\Components\TextInput::make($planning)
        ->required()
        ->numeric()
        ->default(0),
      self::$plannings
    );

    return $form
      ->schema([
        Forms\Components\TextInput::make('year')
          ->default($year)
          ->required()
          ->integer(),
        Forms\Components\Select::make('month')
          ->options($months)
          ->default($month)
          ->required(),
        Forms\Components\Select::make('equipment_id')
          ->options(Equipment::all()->pluck('label', 'id'))
          ->columnSpanFull()
          ->searchable()
          ->required(),
        ...$plannings,
        Forms\Components\TextInput::make('target')
          ->prefix('Rp')
          ->required()
          ->numeric()
          ->hintAction(
            Forms\Components\Actions\Action::make('Calculate target')
              ->action(function (Get $get, Set $set) {
                $equipment = Equipment::find($get('equipment_id'));
                if ($equipment === null) return;
                $weeks = collect(self::$plannings)->map(fn($planningWeek) => $get($planningWeek));
                $set('target', $weeks->sum() * $equipment->price);
              }),
          )
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
        Tables\Columns\TextColumn::make('year')
          ->sortable(),
        Tables\Columns\TextColumn::make('month')
          ->formatStateUsing(fn(string $state): string => Carbon::createFromFormat('m', $state)->format('F'))
          ->sortable(),
        Tables\Columns\TextColumn::make('planning_week_1')
          ->numeric()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('planning_week_2')
          ->numeric()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('planning_week_3')
          ->numeric()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),
        Tables\Columns\TextColumn::make('planning_week_4')
          ->numeric()
          ->sortable()
          ->toggleable(isToggledHiddenByDefault: true),

        Tables\Columns\TextColumn::make('total')
          ->label('Total planning time')
          ->sortable(),
        Tables\Columns\TextColumn::make('target')
          ->label('Total planning price')
          ->money('IDR')
          ->sortable(),

        Tables\Columns\TextColumn::make('actual.total')
          ->label('Total usage time')
          ->sortable(),
        Tables\Columns\TextColumn::make('actual.price')
          ->label('Actual usage')
          ->money('IDR')
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
      ->recordAction(Tables\Actions\ViewAction::class);;
  }

  public static function infolist(Infolists\Infolist $infolist): Infolists\Infolist
  {
    return $infolist
      ->schema([
        Infolists\Components\Section::make('Efficiency Planning Details')
          ->description('The details of the efficiency planning data')
          ->schema([
            Infolists\Components\TextEntry::make('year'),
            Infolists\Components\TextEntry::make('month')->formatStateUsing(fn(string $state): string => Carbon::createFromFormat('m', $state)->format('F')),
            Infolists\Components\TextEntry::make('equipment.code')->label('Equipment Code'),
            Infolists\Components\TextEntry::make('equipment.name')->label('Equipment Name'),
          ])->columns(2),

        Infolists\Components\Section::make('Efficiency Planning Details')
          ->description('The details of the efficiency planning data')
          ->schema([
            Infolists\Components\TextEntry::make('planning_week_1')->badge()->numeric(),
            Infolists\Components\TextEntry::make('planning_week_2')->badge()->numeric(),
            Infolists\Components\TextEntry::make('planning_week_3')->badge()->numeric(),
            Infolists\Components\TextEntry::make('planning_week_4')->badge()->numeric(),
            Infolists\Components\TextEntry::make('target')->money('IDR'),
          ])->columns(5),

        Infolists\Components\Section::make('Actual Weeks')
          ->description('The actual usage data of the equipment')
          ->schema([
            Infolists\Components\TextEntry::make('actual.actual_week_1')->label('Actual week 1')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.actual_week_2')->label('Actual week 2')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.actual_week_3')->label('Actual week 3')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.actual_week_4')->label('Actual week 4')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.price')->money('IDR'),
          ])->columns(5),

        Infolists\Components\Section::make('Efficiency')
          ->description('Summary of equipment efficiency achievement')
          ->schema([
            Infolists\Components\TextEntry::make('total')->label('Total weekly planning')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.total')->label('Total actual usage')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.available.time')->label('Leftover time')->badge()->numeric(),
            Infolists\Components\TextEntry::make('actual.available.price')->label('Leftover budget')->money('IDR'),
          ])->columns(2),
      ])->columns(2);
  }

  public static function getPages(): array
  {
    return [
      'index' => Pages\ListEfficiencyPlannings::route('/'),
      'create' => Pages\CreateEfficiencyPlanning::route('/create'),
      'view' => Pages\ViewEfficiencyPlanning::route('/{record}'),
      'edit' => Pages\EditEfficiencyPlanning::route('/{record}/edit'),
    ];
  }
}
