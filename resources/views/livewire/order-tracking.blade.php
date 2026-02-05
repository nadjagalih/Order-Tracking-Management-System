<div wire:poll.10s="refresh">
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
        <!-- Header dengan Kode Order -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between flex-wrap gap-4">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">{{ $order->order_code }}</h1>
                    <p class="text-gray-600 mt-1">{{ $order->service_type }}</p>
                </div>
                <div>
                    <span class="inline-flex items-center px-4 py-2 rounded-full text-sm font-semibold
                        @if($order->status === 'completed') bg-green-100 text-green-800
                        @elseif($order->status === 'in_progress') bg-blue-100 text-blue-800
                        @elseif($order->status === 'revision_1') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'revision_2') bg-yellow-100 text-yellow-800
                        @elseif($order->status === 'cancelled') bg-red-100 text-red-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        @if($order->status === 'completed') ✅ Selesai
                        @elseif($order->status === 'in_progress') 🔄 Dikerjakan
                        @elseif($order->status === 'revision_1') ✏️ Revisi 1
                        @elseif($order->status === 'revision_2') ✏️ Revisi 2
                        @elseif($order->status === 'cancelled') ❌ Dibatalkan
                        @else 📝 Draft
                        @endif
                    </span>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-5 gap-6">
            <!-- Detail Pesanan -->
            <div class="lg:col-span-2 space-y-6">
                <!-- Info Pesanan -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <h2 class="text-lg font-semibold text-gray-900 mb-4">📋 Detail Pesanan</h2>
                    <dl class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Nama Client</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->client_name }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Projek</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->service_type }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Email Admin</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="mailto:lensakucreative@gmail.com" class="text-blue-600 hover:underline">
                                    lensakucreative@gmail.com
                                </a>
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">No. Telepon Admin</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                <a href="tel:+6285823492273" class="text-blue-600 hover:underline">
                                    +62 858-2349-2273
                                </a>
                            </dd>
                        </div>
                        <div class="md:col-span-2">
                            <dt class="text-xs font-medium text-gray-500 uppercase">Deskripsi</dt>
                            <dd class="mt-1 text-sm text-gray-900 whitespace-pre-line">{{ $order->description }}</dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Estimasi Selesai</dt>
                            <dd class="mt-1 text-sm text-gray-900">
                                {{ $order->estimated_completion?->format('d M Y, H:i') ?? 'Belum ditentukan' }}
                            </dd>
                        </div>
                        <div>
                            <dt class="text-xs font-medium text-gray-500 uppercase">Dibuat Pada</dt>
                            <dd class="mt-1 text-sm text-gray-900">{{ $order->created_at->format('d M Y, H:i') }}</dd>
                        </div>
                    </dl>
                </div>

                <!-- Progress Terbaru -->
                <div class="bg-white rounded-lg shadow-sm p-6">
                    <div class="flex items-center justify-between mb-4">
                        <h2 class="text-lg font-semibold text-gray-900">📈 Progress Terbaru</h2>
                        @if($order->progress->count() > 1)
                        <a href="{{ route('track.timeline', $order->order_code) }}" 
                           class="inline-flex items-center px-3 py-2 text-sm font-medium text-indigo-600 bg-indigo-50 rounded-md hover:bg-indigo-100 transition-colors">
                            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            Lihat Semua Timeline
                        </a>
                        @endif
                    </div>
                    
                    @if($order->progress->count() > 0)
                        @php
                            $latestProgress = $order->progress->first();
                        @endphp
                        <div class="bg-gradient-to-br from-indigo-50 to-blue-50 rounded-lg p-6">
                            <div class="flex items-start">
                                <div class="flex-shrink-0">
                                    <div class="h-10 w-10 rounded-full bg-indigo-500 flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                        </svg>
                                    </div>
                                </div>
                                <div class="ml-4 flex-1">
                                    <div class="flex items-start justify-between">
                                        <div class="flex-1">
                                            <h3 class="text-base font-semibold text-gray-900">{{ $latestProgress->title }}</h3>
                                            <p class="mt-2 text-sm text-gray-600">{{ $latestProgress->description }}</p>
                                            
                                            @if($latestProgress->files)
                                                @php
                                                    $files = is_string($latestProgress->files) ? json_decode($latestProgress->files, true) : $latestProgress->files;
                                                @endphp
                                                @if($files && is_array($files) && count($files) > 0)
                                                <div class="mt-4 space-y-2">
                                                    <p class="text-xs font-medium text-gray-700 uppercase">📎 File Lampiran:</p>
                                                    @foreach($files as $file)
                                                        <a href="{{ $file['url'] }}" 
                                                           target="_blank"
                                                           class="flex items-center p-3 bg-white rounded-lg hover:bg-indigo-50 transition-colors group">
                                                            <svg class="w-4 h-4 text-indigo-600 mr-3 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                            </svg>
                                                            <span class="text-sm font-medium text-gray-900 group-hover:text-indigo-600 flex-1">
                                                                {{ $file['name'] ?? 'Download File' }}
                                                            </span>
                                                            <svg class="w-4 h-4 text-gray-400 group-hover:text-indigo-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                            </svg>
                                                        </a>
                                                    @endforeach
                                                </div>
                                                @endif
                                            @endif
                                        </div>
                                        <div class="ml-4 text-right">
                                            <time class="text-sm font-medium text-gray-900">{{ $latestProgress->created_at->format('d M Y') }}</time>
                                            <br>
                                            <time class="text-xs text-gray-500">{{ $latestProgress->created_at->format('H:i') }}</time>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="text-center py-8">
                            <div class="inline-flex items-center justify-center w-16 h-16 bg-gray-100 rounded-full mb-3">
                                <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <p class="text-gray-500 text-sm">Belum ada progress untuk pesanan ini</p>
                            <p class="text-xs text-gray-400 mt-1">Kami akan update progress secara berkala</p>
                        </div>
                    @endif
                </div>
            </div>

            <div class="lg:col-span-2 space-y-6">
                <!-- Tombol Kembali -->
                <a 
                    href="/track" 
                    class="block w-full text-center px-4 py-2 bg-gray-100 hover:bg-gray-200 text-gray-800 font-medium rounded-lg transition">
                    ← Kembali ke Pencarian
                </a>
            </div>
        </div>
    </div>
</div>
