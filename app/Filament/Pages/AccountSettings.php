<?php

namespace App\Filament\Pages;

use App\Models\User;
use Filament\Forms;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class AccountSettings extends Page implements Forms\Contracts\HasForms
{
    use Forms\Concerns\InteractsWithForms;

    protected static ?string $navigationIcon = 'heroicon-o-cog-6-tooth';
    
    protected static ?string $navigationLabel = 'Pengaturan Akun';
    
    protected static ?string $title = 'Pengaturan Akun';

    protected static string $view = 'filament.pages.account-settings';
    
    protected static ?int $navigationSort = 99;

    public ?array $profileData = [];
    public ?array $passwordData = [];

    public function mount(): void
    {
        $this->fillForms();
    }

    protected function fillForms(): void
    {
        /** @var User $user */
        $user = Auth::user();
        
        $this->profileData = [
            'name' => $user->name,
            'email' => $user->email,
        ];

        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];
    }

    public function profileForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Informasi Profil')
                    ->description('Update nama dan email Anda')
                    ->schema([
                        TextInput::make('name')
                            ->label('Nama')
                            ->required()
                            ->maxLength(255),
                        
                        TextInput::make('email')
                            ->label('Email')
                            ->email()
                            ->required()
                            ->unique(table: 'users', column: 'email', ignorable: fn () => Auth::user())
                            ->maxLength(255),
                    ]),
            ])
            ->statePath('profileData');
    }

    public function passwordForm(Form $form): Form
    {
        return $form
            ->schema([
                Section::make('Ganti Password')
                    ->description('Pastikan password Anda kuat dan aman')
                    ->schema([
                        TextInput::make('current_password')
                            ->label('Password Saat Ini')
                            ->password()
                            ->required()
                            ->currentPassword()
                            ->revealable(),
                        
                        TextInput::make('password')
                            ->label('Password Baru')
                            ->password()
                            ->required()
                            ->rule(Password::default())
                            ->different('current_password')
                            ->same('password_confirmation')
                            ->revealable(),
                        
                        TextInput::make('password_confirmation')
                            ->label('Konfirmasi Password Baru')
                            ->password()
                            ->required()
                            ->revealable(),
                    ]),
            ])
            ->statePath('passwordData');
    }

    public function updateProfile(): void
    {
        $data = $this->profileForm->getState();

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
        ]);

        Notification::make()
            ->success()
            ->title('Profil berhasil diupdate')
            ->body('Informasi profil Anda telah berhasil diperbarui.')
            ->send();
    }

    public function updatePassword(): void
    {
        $data = $this->passwordForm->getState();

        /** @var User $user */
        $user = Auth::user();
        $user->update([
            'password' => Hash::make($data['password']),
        ]);

        // Reset password form
        $this->passwordData = [
            'current_password' => '',
            'password' => '',
            'password_confirmation' => '',
        ];

        Notification::make()
            ->success()
            ->title('Password berhasil diubah')
            ->body('Password Anda telah berhasil diperbarui.')
            ->send();
    }

    protected function getForms(): array
    {
        return [
            'profileForm',
            'passwordForm',
        ];
    }
}
