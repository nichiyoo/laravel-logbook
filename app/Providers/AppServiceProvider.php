<?php

namespace App\Providers;

use Filament\Facades\Filament;
use Filament\Navigation\NavigationItem;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
  /**
   * Register any application services.
   */
  public function register(): void
  {
    //
  }

  /**
   * Bootstrap any application services.
   */
  public function boot(): void
  {
    Model::unguard();

    Table::configureUsing(function ($table) {
      Table::$defaultCurrency = 'IDR';
      Table::$defaultDateDisplayFormat = 'F j Y';
      Table::$defaultDateTimeDisplayFormat = 'F j, Y g:i A';
      Table::$defaultTimeDisplayFormat = 'g:i A';
    });

    ImageColumn::configureUsing(function ($column) {
      $column->size(32);
    });

    Filament::serving(function () {
      Filament::registerNavigationItems([
        NavigationItem::make('Profile')
          ->group('Application')
          ->url('/admin/profile')
          ->icon('heroicon-o-user')
          ->activeIcon('heroicon-o-user')
          ->isActiveWhen(fn() => request()->routeIs('filament.admin.auth.profile')),
      ]);
    });
  }
}
