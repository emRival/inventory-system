<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Item QR</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8fafc;
        }

        .letterhead {
            background: linear-gradient(135deg, #2563eb 0%, #1e40af 100%);
        }

        .qr-container {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
        }

        .info-item {
            border-bottom: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }

        .info-item:hover {
            background-color: #f8fafc;
        }
    </style>
</head>

<body class="min-h-screen py-8 px-4">
    @php

        $logoBase64 = base64_encode(file_get_contents(storage_path('app/public/logo/logo.png'))); // Placeholder logo minimalis
    @endphp
    <div class="max-w-4xl mx-auto">
        <!-- Letterhead with logo and address -->
        <div class="letterhead rounded-t-lg p-6 flex items-center justify-between text-white mb-8">
            <div class="flex items-center">
            <div class="bg-white p-3 rounded-lg mr-4">
                <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Company Logo" class="h-12 w-auto">
            </div>
            <div>
                <h1 class="text-2xl font-bold">DigiScan</h1>
                <p class="text-blue-100">Inventory Management System</p>
            </div>
            </div>
            <div class="hidden md:block text-center">
            <p class="text-blue-100">Cikarang, Jawa Barat</p>
            <p class="text-blue-100">Indonesia</p>
            </div>
        </div>

        <!-- QR Card -->
        <div class="qr-container bg-white rounded-lg shadow-lg overflow-hidden relative">

            <div class="p-6 bg-gradient-to-r from-blue-50 to-indigo-50">
                <h2 class="text-2xl font-bold text-gray-800 mb-2 flex items-center">
                    <i class="fas fa-qrcode mr-3 text-blue-600"></i>
                    Item Details
                </h2>
                <p class="text-gray-600">Scan QR code below to view this item</p>
            </div>

            <div class="p-6">
                <div class="flex flex-col md:flex-row gap-8">
                    <!-- QR Code Section -->
                    <div class="w-full md:w-1/3 flex flex-col items-center">
                        <div class="bg-white p-4 rounded-lg border border-gray-200 mb-4">
                            <img src="https://qrcode.tec-it.com/API/QRCode?size=medium&data={{ $unit->qr_code }}"
                                alt="QR Code" class="w-48 h-48">
                        </div>
                        <div class="text-center bg-blue-50 px-4 py-2 rounded-lg w-full">
                            <p class="font-medium text-blue-700">QR Code:</p>
                            <p class="text-gray-700 font-mono">{{ $unit->qr_code }}</p>
                        </div>
                    </div>

                    <!-- Item Details Section -->
                    <div class="w-full md:w-2/3">
                        <div class="space-y-4">
                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-blue-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-info-circle text-blue-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Status</p>
                                        <p class="font-medium capitalize">{{ ucfirst($unit->status) }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-yellow-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-sticky-note text-yellow-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Catatan</p>
                                        <p class="font-medium">{{ $unit->note ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-red-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-sticky-note text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Catatan Pengembalian</p>
                                        <p class="font-medium">{{ $unit->return_note ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-green-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-map-marker-alt text-green-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Distribusi ke</p>
                                        <p class="font-medium">{{ $unit->distribution->sector->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-purple-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-box text-purple-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Produk</p>
                                        <p class="font-medium">{{ $unit->distribution->product->name ?? '-' }}</p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-red-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-heartbeat text-red-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Kondisi</p>
                                        <p class="font-medium capitalize">{{ $unit->distribution->condition ?? '-' }}
                                        </p>
                                    </div>
                                </div>
                            </div>

                            <div class="info-item p-3 rounded-lg">
                                <div class="flex items-center">
                                    <div class="bg-gray-100 p-2 rounded-full mr-3">
                                        <i class="fas fa-calendar-alt text-gray-600"></i>
                                    </div>
                                    <div>
                                        <p class="text-sm text-gray-500">Dibuat</p>
                                        <p class="font-medium">{{ $unit->created_at->format('d M Y H:i') }}</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 border-t border-gray-200">
                <div class="flex justify-between items-center text-sm text-gray-500">
                    <p>Generated on {{ date('d M Y H:i') }}</p>
                    <p class="flex items-center">
                        <i class="fas fa-lock mr-1"></i> Secure Document
                    </p>
                </div>
            </div>

            <!-- Floating Edit Button -->
            <a href="{{ route('filament.admin.resources.item-units.edit', $unit->qr_code) }}"
                class="absolute top-4 right-4 bg-blue-600 text-white p-2 rounded-full shadow hover:bg-blue-700 transition"
                title="Edit Item">
                <i class="fas fa-pen-to-square"></i>
            </a>
        </div>
    </div>
</body>

</html>
