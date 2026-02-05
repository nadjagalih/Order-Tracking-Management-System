<div>
    <div class="max-w-md mx-auto">
        <div class="rounded-lg p-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-14 h-14 bg-blue-100 rounded-full mb-3">
                    <svg class="w-7 h-7 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </div>
                <h2 class="text-xl font-bold text-gray-900">Lacak Pesanan Anda</h2>
                <p class="text-sm text-gray-600 mt-1">Masukkan kode pesanan untuk melihat progress</p>
            </div>

            <form wire:submit="search">
                <div class="mb-4">
                    <label for="orderCode" class="block text-sm font-medium text-gray-700 mb-1.5">
                        Kode Pesanan
                    </label>
                    <input 
                        type="text" 
                        wire:model="orderCode" 
                        id="orderCode"
                        class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent uppercase text-sm"
                        placeholder="ORD-2026-XXXXXXXX"
                        autofocus
                    >
                    @error('orderCode')
                        <p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <button 
                    type="submit"
                    class="w-full bg-blue-600 hover:bg-blue-700 text-white font-semibold py-2.5 px-4 rounded-lg transition duration-200 flex items-center justify-center text-sm"
                >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    Lacak Progress
                </button>
            </form>

            <div class="mt-6 p-3 bg-blue-50 rounded-lg">
                <p class="text-xs text-blue-800">
                    <strong>💡 </strong> Kode pesanan dikirim melalui WhatsApp
                </p>
            </div>
        </div>
    </div>
</div>
