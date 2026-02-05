<x-filament-widgets::widget>
    <x-filament::section>
        <div class="flex items-center justify-between">
            <div class="flex items-center space-x-4">
                <div class="flex h-16 w-16 items-center justify-center rounded-full bg-gray-900 dark:bg-gray-100">
                    <span class="text-2xl font-bold text-white dark:text-gray-900">
                        {{ strtoupper(substr(auth()->user()->name, 0, 1)) }}
                    </span>
                </div>
                <div>
                    <h2 class="text-xl font-semibold text-gray-900 dark:text-white">Welcome</h2>
                    <p class="text-lg text-gray-600 dark:text-gray-300">{{ auth()->user()->name }}</p>
                </div>
            </div>
            <div>
                <x-filament::button
                    color="gray"
                    icon="heroicon-o-arrow-right-on-rectangle"
                    tag="a"
                    href="{{ route('filament.admin.auth.logout') }}"
                    onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    Sign out
                </x-filament::button>
                <form id="logout-form" action="{{ route('filament.admin.auth.logout') }}" method="POST" class="hidden">
                    @csrf
                </form>
            </div>
        </div>
    </x-filament::section>
</x-filament-widgets::widget>
