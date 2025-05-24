<?php

namespace App\Filament\Admin\Resources\UserResource\Pages;

use App\Filament\Admin\Resources\UserResource;
use Filament\Actions;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;

class EditUser extends EditRecord
{
    protected static string $resource = UserResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Actions\DeleteAction::make(),
            \Filament\Actions\Action::make('resetPassword')
                ->label('Reset Password')
                ->color('warning')
                ->action(function () {
                    $user = $this->record;
                    $newPassword = 'password123'; // Atur password baru di sini
                    $user->password = bcrypt($newPassword);
                    $user->save();

                    Notification::make()
                        ->title('Password berhasil direset')
                        ->body("Password baru: {$newPassword}")
                        ->success()
                        ->send();
                }),
        ];
    }
}