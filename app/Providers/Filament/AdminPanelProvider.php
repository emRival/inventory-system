<?php

namespace App\Providers\Filament;

use BezhanSalleh\FilamentShield\FilamentShieldPlugin;
use Filament\Http\Middleware\Authenticate;
use Filament\Http\Middleware\AuthenticateSession;
use Filament\Http\Middleware\DisableBladeIconComponents;
use Filament\Http\Middleware\DispatchServingFilamentEvent;
use Filament\Navigation\MenuItem;
use Filament\Navigation\NavigationBuilder;
use Filament\Navigation\NavigationItem;
use Filament\Pages;
use Filament\Panel;
use Filament\PanelProvider;
use Filament\Support\Colors\Color;
use Filament\Widgets;
use Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse;
use Illuminate\Cookie\Middleware\EncryptCookies;
use Illuminate\Foundation\Http\Middleware\VerifyCsrfToken;
use Illuminate\Routing\Middleware\SubstituteBindings;
use Illuminate\Session\Middleware\StartSession;
use Illuminate\Support\Facades\Auth;
use Illuminate\View\Middleware\ShareErrorsFromSession;
use Joaopaulolndev\FilamentEditProfile\FilamentEditProfilePlugin;
use Illuminate\Support\Str;
use Joaopaulolndev\FilamentEditProfile\Pages\EditProfilePage;


class AdminPanelProvider extends PanelProvider
{


    public function panel(Panel $panel): Panel
    {
        return $panel
            ->default()
            ->id('admin')
            ->path('admin')
            ->passwordReset()
            ->emailVerification()
            ->databaseNotifications()
            ->login(\Filament\Pages\Auth\Login::class)
            ->colors([
                'primary' => Color::Amber,
                'secondary' => Color::Gray,
                'success' => Color::Emerald,
                'danger' => Color::Red,
                'warning' => Color::Yellow,
                'info' => Color::Blue,
            ])
            ->userMenuItems([
                'profile' => MenuItem::make()
                    ->label(fn() => Auth::user()->name)
                    ->url(fn(): string => EditProfilePage::getUrl())
                    ->icon('heroicon-m-user-circle'),
                'roles' => MenuItem::make()
                    ->label(fn() => __('Role :') . ' ' . ucwords(str_replace('_', ' ', Auth::user()->roles[0]['name'] ?? 'Unknown')))
                    ->icon('heroicon-o-shield-check'),
                // roles "anda login sebagai

            ])
            ->discoverResources(in: app_path('Filament/Admin/Resources'), for: 'App\\Filament\\Admin\\Resources')
            ->discoverPages(in: app_path('Filament/Admin/Pages'), for: 'App\\Filament\\Admin\\Pages')
            ->pages([
                Pages\Dashboard::class,
            ])
            ->discoverWidgets(in: app_path('Filament/Admin/Widgets'), for: 'App\\Filament\\Admin\\Widgets')
            ->widgets([
                Widgets\AccountWidget::class,
                Widgets\FilamentInfoWidget::class,
            ])
            ->middleware([
                EncryptCookies::class,
                AddQueuedCookiesToResponse::class,
                StartSession::class,
                AuthenticateSession::class,
                ShareErrorsFromSession::class,
                VerifyCsrfToken::class,
                SubstituteBindings::class,
                DisableBladeIconComponents::class,
                DispatchServingFilamentEvent::class,
            ])
            ->plugins([
                FilamentShieldPlugin::make(),
                FilamentEditProfilePlugin::make()
                    ->slug('edit-profile') //Set Manual
                    // ->setTitle('My Profile')
                    // ->setNavigationLabel('My Profile')
                    // ->setIcon('heroicon-o-user')
                    // ->shouldShowEmailForm(false)
                    ->shouldShowEditProfileForm()

                    ->shouldShowDeleteAccountForm(false)
                    ->shouldRegisterNavigation(false)
            ])




            ->authMiddleware([
                Authenticate::class,
            ]);
    }
}