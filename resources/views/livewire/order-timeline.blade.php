<div>
    <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        
        <!-- Header -->
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center">
                <div class="bg-indigo-600 rounded-full p-2 mr-3">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">Timeline Progress Lengkap</h1>
                    <p class="text-sm text-gray-500">Order #{{ $order->order_code }}</p>
                </div>
            </div>
            <a href="{{ route('track.show', $order->order_code) }}" 
               class="inline-flex items-center px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50">
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>

        <!-- Order Summary Card -->
        <div class="bg-white rounded-lg shadow-sm p-6 mb-6">
            <div class="flex items-center justify-between">
                <div>
                    <h2 class="text-lg font-semibold text-gray-900">{{ $order->client_name }}</h2>
                    <p class="text-sm text-gray-500">{{ $order->service_type }}</p>
                </div>
                <div class="text-right">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium {{ $order->status_badge }}">
                        {{ $order->status_label }}
                    </span>
                    <p class="text-xs text-gray-500 mt-1">Status Terkini</p>
                </div>
            </div>
        </div>

        <!-- Timeline -->
        <div class="bg-white rounded-lg shadow-sm p-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-6 flex items-center">
                <svg class="w-5 h-5 text-indigo-600 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"></path>
                </svg>
                Semua Progress ({{ $order->progress->count() }})
            </h2>

            @if($order->progress->count() > 0)
            <div class="flow-root">
                <ul role="list" class="-mb-8">
                    @foreach($order->progress->sortByDesc('created_at') as $progress)
                    <li>
                        <div class="relative pb-8">
                            @if(!$loop->last)
                            <span class="absolute top-5 left-5 -ml-px h-full w-0.5 bg-gray-200" aria-hidden="true"></span>
                            @endif
                            <div class="relative flex items-start space-x-4">
                                <div class="relative">
                                    <span class="h-10 w-10 rounded-full {{ $loop->first ? 'bg-indigo-500 ring-4 ring-indigo-100' : 'bg-gray-400' }} flex items-center justify-center">
                                        <svg class="w-5 h-5 text-white" fill="currentColor" viewBox="0 0 20 20">
                                            @if($loop->first)
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                            @else
                                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"></path>
                                            @endif
                                        </svg>
                                    </span>
                                </div>
                                <div class="min-w-0 flex-1">
                                    <div class="bg-gray-50 rounded-lg p-5 {{ $loop->first ? 'border-2 border-indigo-200' : 'border border-gray-200' }}">
                                        <div class="flex items-start justify-between">
                                            <div class="flex-1">
                                                <div class="flex items-center gap-2">
                                                    <h3 class="text-base font-semibold text-gray-900">{{ $progress->title }}</h3>
                                                    @if($loop->first)
                                                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-indigo-100 text-indigo-800">
                                                        Terbaru
                                                    </span>
                                                    @endif
                                                </div>
                                                <p class="mt-2 text-sm text-gray-600 leading-relaxed">{{ $progress->description }}</p>
                                                
                                                @if($progress->files)
                                                @php
                                                    $files = is_string($progress->files) ? json_decode($progress->files, true) : $progress->files;
                                                @endphp
                                                @if($files && is_array($files) && count($files) > 0)
                                                <div class="mt-4 space-y-2">
                                                    <p class="text-xs font-medium text-gray-700 uppercase flex items-center">
                                                        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                                                        </svg>
                                                        {{ count($files) }} File Lampiran
                                                    </p>
                                                    @foreach($files as $file)
                                                    <a href="{{ $file['url'] }}" 
                                                       target="_blank"
                                                       class="flex items-center p-3 bg-white rounded-lg border border-gray-200 hover:border-indigo-300 hover:bg-indigo-50 transition-colors group">
                                                        <div class="flex-shrink-0">
                                                            <div class="h-10 w-10 rounded-lg bg-indigo-100 flex items-center justify-center group-hover:bg-indigo-200">
                                                                <svg class="w-5 h-5 text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                                                </svg>
                                                            </div>
                                                        </div>
                                                        <div class="ml-3 flex-1">
                                                            <p class="text-sm font-medium text-gray-900 group-hover:text-indigo-600">
                                                                {{ $file['name'] ?? 'Download File' }}
                                                            </p>
                                                            <p class="text-xs text-gray-500">Klik untuk membuka</p>
                                                        </div>
                                                        <svg class="w-5 h-5 text-gray-400 group-hover:text-indigo-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"></path>
                                                        </svg>
                                                    </a>
                                                    @endforeach
                                                </div>
                                                @endif
                                                @endif
                                                
                                                <div class="mt-3 flex items-center text-xs text-gray-500">
                                                    @if($progress->creator)
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                                    </svg>
                                                    <span class="mr-3">{{ $progress->creator->name }}</span>
                                                    @endif
                                                    <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    <time datetime="{{ $progress->created_at->toIso8601String() }}">
                                                        {{ $progress->created_at->format('d M Y, H:i') }}
                                                    </time>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </li>
                    @endforeach
                </ul>
            </div>
            @else
            <div class="text-center py-12">
                <svg class="mx-auto h-12 w-12 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <p class="text-gray-500 mt-3">Belum ada progress untuk order ini</p>
                <p class="text-sm text-gray-400 mt-1">Kami akan update progress secara berkala</p>
            </div>
            @endif
        </div>
    </div>
</div>
