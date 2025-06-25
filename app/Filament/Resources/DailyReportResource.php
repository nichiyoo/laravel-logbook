<?php

namespace App\Filament\Resources;

use Filament\Forms;
use Filament\Tables;
use Filament\Forms\Form;
use Asmit\FilamentUpload;
use Filament\Tables\Table;
use App\Models\DailyReport;
use Filament\Resources\Resource;
use App\Filament\Resources\DailyReportResource\Pages;

class DailyReportResource extends Resource
{
  protected static ?string $model = DailyReport::class;
  protected static ?string $navigationIcon = 'heroicon-o-calendar';

  public static function getModelLabel(): string
  {
    return __('Daily Reports');
  }

  public static function getNavigationGroup(): ?string
  {
    return __('Log Management');
  }

  public static function form(Form $form): Form
  {
    return $form
      ->schema([
        Forms\Components\DatePicker::make('date')
          ->displayFormat('F j, Y')
          ->native(false)
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
        FilamentUpload\Forms\Components\AdvancedFileUpload::make('report')
          ->label('Upload report')
          ->acceptedFileTypes([
            'application/pdf',
          ])
          ->pdfPreviewHeight(400)
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
        Tables\Columns\TextColumn::make('date')
          ->date()
          ->sortable(),
        Tables\Columns\TextColumn::make('user.name')
          ->sortable(),
        Tables\Columns\TextColumn::make('shift.label')
          ->badge()
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
      'index' => Pages\ListDailyReports::route('/'),
      'create' => Pages\CreateDailyReport::route('/create'),
      'view' => Pages\ViewDailyReport::route('/{record}'),
      'edit' => Pages\EditDailyReport::route('/{record}/edit'),
    ];
  }
}
