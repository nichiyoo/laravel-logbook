<?php

namespace App\Filament\Resources\LogbookResource\Pages;

use App\Filament\Resources\LogbookResource;
use Filament\Actions;
use Filament\Resources\Pages\ViewRecord;

class ViewLogbook extends ViewRecord
{
    protected static string $resource = LogbookResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\EditAction::make(),
        ];
    }
}
