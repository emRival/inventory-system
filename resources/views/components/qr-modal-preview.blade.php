@php
    $unit = $getRecord();
    $url = $unit?->qr_code
        ? 'https://quickchart.io/qr?text=' . urlencode(route('item-units.show', $unit->qr_code)) . '&size=500'
        : null;

    // Pastikan relasi sudah di-load
    $unit?->loadMissing(['distribution.product', 'distribution.sector']);
@endphp

@if ($url)
    <div x-data="{ open: false }" class="flex items-center h-full py-2 cursor-pointer" @click.stop="open = true" x-cloak>

        <img src="{{ asset('storage/qrs/' . $getRecord()->qr_code . '.png') }}"
            class="w-20 h-20 rounded-lg shadow-md border-2 border-white/20 hover:ring-2 hover:ring-blue-400 transition"
            alt="QR Code" />

        <!-- Modal -->
        <template x-teleport="body">
            <div x-show="open" x-transition:enter="ease-out duration-300" x-transition:enter-start="opacity-0 scale-95"
                x-transition:enter-end="opacity-100 scale-100" x-transition:leave="ease-in duration-200"
                x-transition:leave-start="opacity-100 scale-100" x-transition:leave-end="opacity-0 scale-95"
                class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[999] flex items-center justify-center p-4" x-cloak>
                <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full overflow-hidden relative"
                    @click.outside="open = false">
                    <!-- Header -->
                    <div class="bg-blue-600 p-4 flex justify-between items-start">
                        <div>
                            <h3 class="text-lg font-bold text-black">Detail QR Code</h3>

                        </div>
                        <button @click="open = false"
                            class="text-black hover:text-blue-200 transition-colors p-1 -mt-2 -mr-2">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <!-- Content -->
                    <div class="grid md:grid-cols-2 gap-6 p-6">
                        <!-- QR Section -->
                        <div class="space-y-4">
                            <div class="bg-gray-50 p-4 rounded-lg border border-gray-200">
                                <img src="{{ $url }}" class="w-full h-auto" alt="QR Full" />
                            </div>
                            <div class="flex items-center space-x-2 text-sm">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor"
                                    viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                </svg>
                                <span class="font-medium text-gray-600">Status:</span>
                                <span
                                    class="px-2 py-1 rounded-full text-sm {{ $unit->status === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' }}">
                                    {{ ucfirst($unit->status) }}
                                </span>
                            </div>
                        </div>

                        <!-- Detail Section -->
                        <div class="space-y-4">
                            <div class="space-y-3">
                                <!-- Code Qr -->
                                <div class="flex items-start space-x-2">
                                    <svg width="22px" height="22px" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path
                                            d="M3 9h6V3H3zm1-5h4v4H4zm1 1h2v2H5zm10 4h6V3h-6zm1-5h4v4h-4zm1 1h2v2h-2zM3 21h6v-6H3zm1-5h4v4H4zm1 1h2v2H5zm15 2h1v2h-2v-3h1zm0-3h1v1h-1zm0-1v1h-1v-1zm-10 2h1v4h-1v-4zm-4-7v2H4v-1H3v-1h3zm4-3h1v1h-1zm3-3v2h-1V3h2v1zm-3 0h1v1h-1zm10 8h1v2h-2v-1h1zm-1-2v1h-2v2h-2v-1h1v-2h3zm-7 4h-1v-1h-1v-1h2v2zm6 2h1v1h-1zm2-5v1h-1v-1zm-9 3v1h-1v-1zm6 5h1v2h-2v-2zm-3 0h1v1h-1v1h-2v-1h1v-1zm0-1v-1h2v1zm0-5h1v3h-1v1h-1v1h-1v-2h-1v-1h3v-1h-1v-1zm-9 0v1H4v-1zm12 4h-1v-1h1zm1-2h-2v-1h2zM8 10h1v1H8v1h1v2H8v-1H7v1H6v-2h1v-2zm3 0V8h3v3h-2v-1h1V9h-1v1zm0-4h1v1h-1zm-1 4h1v1h-1zm3-3V6h1v1z" />
                                        <path fill="none" d="M0 0h24v24H0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">QR Code</p>
                                        <p class="text-gray-800 break-words">
                                            @if ($unit->qr_code)
                                                @foreach (str_split($unit->qr_code, 20) as $chunk)
                                                    {{ $chunk }}<br>
                                                @endforeach
                                            @else
                                                Tidak ada catatan
                                            @endif
                                        </p>
                                    </div>
                                </div>
                                <!-- Catatan -->
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Catatan</p>
                                        <p class="text-gray-800">{{ $unit->note ?? 'Tidak ada catatan' }}</p>
                                    </div>
                                </div>

                                <!-- Produk -->
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Produk</p>
                                        <p class="text-gray-800">
                                            {{ $unit->distribution->product->name ?? 'Belum terdaftar' }}
                                        </p>
                                    </div>
                                </div>

                                <!-- Kondisi -->
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Kondisi</p>
                                        <p class="text-gray-800">{{ $unit->distribution->condition ?? '-' }}</p>
                                    </div>
                                </div>

                                <!-- Sektor -->
                                <div class="flex items-start space-x-2">
                                    <svg class="w-5 h-5 text-gray-500 mt-0.5" fill="none" stroke="currentColor"
                                        viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 11a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    <div>
                                        <p class="text-sm font-medium text-gray-600">Sektor</p>
                                        <p class="text-gray-800">
                                            {{ $unit->distribution->sector->name ?? 'Belum ditentukan' }}
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </template>
    </div>
@else
    <span class="text-gray-400 italic">Tidak ada QR</span>
@endif
