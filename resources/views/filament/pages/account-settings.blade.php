<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Profile Information Section --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-x-3">
                    <x-filament::icon
                        icon="heroicon-o-user-circle"
                        class="h-6 w-6 text-gray-400"
                    />
                    <span>Informasi Profil</span>
                </div>
            </x-slot>

            <x-slot name="description">
                Update informasi nama dan email akun Anda
            </x-slot>

            <form wire:submit="updateProfile">
                {{ $this->profileForm }}

                <div class="mt-6">
                    <x-filament::button 
                        type="submit"
                        wire:loading.attr="disabled"
                        wire:target="updateProfile"
                    >
                        <span wire:loading.remove wire:target="updateProfile">
                            <x-filament::icon
                                icon="heroicon-o-check-circle"
                                class="h-5 w-5 mr-1"
                            />
                            Simpan Perubahan
                        </span>
                        <span wire:loading wire:target="updateProfile" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Menyimpan...
                        </span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Password Section --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-x-3">
                    <x-filament::icon
                        icon="heroicon-o-lock-closed"
                        class="h-6 w-6 text-gray-400"
                    />
                    <span>Keamanan Password</span>
                </div>
            </x-slot>

            <x-slot name="description">
                Ganti password Anda secara berkala untuk keamanan akun
            </x-slot>

            <form wire:submit="updatePassword">
                {{ $this->passwordForm }}

                <div class="mt-6">
                    <x-filament::button 
                        type="submit" 
                        color="warning"
                        wire:loading.attr="disabled"
                        wire:target="updatePassword"
                    >
                        <span wire:loading.remove wire:target="updatePassword">
                            <x-filament::icon
                                icon="heroicon-o-key"
                                class="h-5 w-5 mr-1"
                            />
                            Ganti Password
                        </span>
                        <span wire:loading wire:target="updatePassword" class="flex items-center">
                            <svg class="animate-spin h-5 w-5 mr-2" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            Memproses...
                        </span>
                    </x-filament::button>
                </div>
            </form>
        </x-filament::section>

        {{-- Account Info --}}
        <x-filament::section>
            <x-slot name="heading">
                <div class="flex items-center gap-x-3">
                    <x-filament::icon
                        icon="heroicon-o-information-circle"
                        class="h-6 w-6 text-gray-400"
                    />
                    <span>Informasi Akun</span>
                </div>
            </x-slot>

            <dl class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Tanggal Bergabung
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ auth()->user()->created_at?->format('d F Y') ?? 'N/A' }}
                    </dd>
                </div>
                <div>
                    <dt class="text-sm font-medium text-gray-500 dark:text-gray-400">
                        Terakhir Update
                    </dt>
                    <dd class="mt-1 text-sm text-gray-900 dark:text-gray-100">
                        {{ auth()->user()->updated_at?->format('d F Y') ?? 'N/A' }}
                    </dd>
                </div>
            </dl>
        </x-filament::section>
    </div>
</x-filament-panels::page>

