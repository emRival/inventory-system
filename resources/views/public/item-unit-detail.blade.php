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
    <div class="max-w-4xl mx-auto">
        <!-- Letterhead with logo and address -->
        <div class="letterhead rounded-t-lg p-6 flex items-center justify-between text-white mb-8">
            <div class="flex items-center">
                <div class="bg-white p-3 rounded-lg mr-4">
                    <img src="data:image/png;base64,iVBORw0KGgoAAAANSUhEUgAAARMAAAC3CAMAAAAGjUrGAAABKVBMVEX///8AbbH///1YWFgArekBbLEArudRUVFaWloCreVMTExVVVX//v9SUlKOGBkBbLPtvBuVlZXu7u4AZay+vr59fX23t7fo6OidnZ1JSUkAseYAY6bl9/8AbLjX19fd3d3x//9jY2MApuGEAAD//++oqKjs2Nre/P4AbKn//+gxr9UAZbWy5fNnn8DR+vvwuR7mvzpknsmZwdfVvb88g7ViweSu0+AXqtcAquuGsNCp6Pm10OmI0ejwzFlpaWmva2zBl5nL8fpDuOWHh4d0dHSMBhDK3+rfvMN4AAUAotNHvt160PDLoqSPGBj/8vCWSEd7m749grxjytxLkrYdbaCC0uLe+OvwyWs9jcD/+tkqd6BVnsgAZp7hvRnsz07478Diy2Ofv846Ojr2MduNAAAI9ElEQVR4nO2cC3fayBWAB8lGQiOVRQgLgQFBwpo4TlJ7HZu4XloWmq6zj9Zbd3eT3W2b/P8f0Xv1mtHDjc92bah0v5wcW3gkz3zcuXM1ImGMIAiCIAiCIAiCIAiCIAiCIAiCIAiCIAiCIAiCIAjiDiipA+W2ZpXB9GUYOUGWhwnnh5e+aZqb7tHmOZrvJNQbhz6FCTiZ1GN6vfr8kuYOU1JOGpPDTXdoC0g7qTcajPJJ1sk5OSlwUnbkhbV4ka2ck5SG4vWkck4Y++yPMX9hflGDCjr506M/R3z5aWEDcLIjOalXwsnvIh59Vjh7lOVVysmk/IVsykkhB4eyk97k6GE7uAE+7oRdTBqNXnS/U+/VD32IJ/gTAfeEZski56NOYMAXh5N5wKTRa9Tnb577Pos3DwIvD9zn++bjcWIqir+MOHoJyaV39YXg8Kv9KjpJFTEXVziP5jEQQOfLshX7d8gnEhASl42dDOf+vffyYfn60TcRd3SynGSUNK6W99/NB+Xbvz6K+DLjpCBNYD49mMwzTsq3Opufxvzt3en19fVpxPVJvm3opJ4JlPI5ERy324PB4GnMq3wLLEeq4CSuvRRwUgN2mwG77Qo7ERy3dyUG5IRlnDTJCUJO8pCTPOQkz52cKPvzbHFfbifNO8VJ1sm81E7uEifLRnbuzMt2vyNxPEjVJ1Ftb5p45xPvqSnsOwiTeq/Xa8RMvvA32u37xJ/VZCf9s/BlEzeVklqXXU7moCPaQAmi5Hx/s/2+T076spT23+P9+eXRhQB3IRvnb17GvHl56W+0178l2Y0A/6S/O6u1Abj9a/f7P/nhXoH/8upqkhDExXcHmSuVZfMRxnH2TOLmaXMwmJ38IeTsddzuzSTMGiKlYvrA7UhFnlKlwPSf9dsSs9ms+aPPkjFGe6zLyU6gpB4vNvPJkinhFm3pnLCTcGcgAr6d3fgsN8jLndhJxA4+Gy3px/z8m2azlqIfFCRZJ416gZOSst/fzTo5Y8njvaRZtZzMahnaZ6xg7mSdlPlzBeQkDznJQ07ykJM85CQPOclT6CRfrFfdyQk5yTppnvpKro59flVvpDmPthnKdfcXUOAEivusE1P5x5UIkXojVduXz0lNbNJHTgY3B0yeO/jVZBfnVynOHz+JeFw6J+1BuxnRDu4GQc5Negc++KAn8w+ey3z/+5gf3irl2jTYP/0x4fp61g+0tPvHZ68FJqYOHLYpUN6++CTmB6VUSjL7sf7ZdRunEO7ECmav9lFH+LngCPY2NvLixeclmzy5PepnbUwpOJ9246Q7eDrLP6d4koTJJ5/f8s9bSoKi+KdNsCApwQxTe+dnp0fKSamBSXDUBycD2ckM1ufcJ/2q4wR5l40TnD6zbKtqOfmpPcg6qe3+7GdaVSefIMehk/TW9c/ZLPsEFpyIsq07BdzNyeNfEin/vO3fVZaH43Yz76SfceIr/0oK2X9XIU7u4AQsPE7YTD8fkjs5qRjkJA85yUNO8pCTPMdPyUmWE3KSY7+ojq22E4W96tdmu+l7wIo7YSZ71q+RExnchH81a2eouBN8mrP/+v37E5n3/qb7tWkKnteU7HnFryB4XGFK/wVbuZ5q/Q+k/oeyzXWDIAiCIP5fsV3XtaXvkwPEHTqOM3TzZ4hmwYFo4UbYNsuflXlNXN38r+0enD2DG93wW3thaIaejM/cW2iGocHfxZ5ceLQ8y7KMdXgw+qBBCy85qQMnBOiL7lD+PSvP8BzpGK/uGZZmaPpUdtA1NG/vNxvdr2PPULXQibmwVGucjM5dG2oEN8YjcUZLC14LB+JoOhx4yag6XA8IzlrZqbMMyYk7NlRsBX+5oUr2utBu4060yIm55jrXk1HY2GcIm4WuWTBAQ0yPwImuheNY8bwTiCzDM+AH2tiWz5KcuIbKdUvTx2NL42BFSAEn2tY4gdGoQkkwOKPlwu2MOwU9fJ38BJ3oOm/h97bOVT3jRHPckTty1tBMW4mzLNnJGo114erMnnKQMk5+sk1OVhANYuKwIXhI+gYTRPeStxKdrLg6DpvxzpqrhnACIRW1hNGp0llcF07g6sLQ0AD7SbstmjswUEuXkwa8eZ3kaGXpVvKWQ1M+tfQgr041bXqbE7bgqtUVF5ScgFJxPczL3jQ+2BYnU9YyONflNRcHKkId3le+iNcecKINdd3CYeiQCha3OXEgUBbxD1JOYMaJCGK4JCdvx9Y4gYzBuazEhqXDEGHj4hIUjxuzpbtWrRa+zg37Vidwli7OkpzYWippp9gSJ+oYEiUfya9Cr3XNlQ/VxFngZApJ0oRIgNw7vs0JnqUVOXEhPwWn2N1pxLblE6wlcOI4ojLDldgSi1AwOuEEQmikYV5cWZCHb3difcyJi+UfsnX5BJVAlEw/iARiW1zNxomYO+DEHHNIKDrXRujEK3aCtUrh3DHiOMEqGAoUTGkRW7IWq1xFJdC3ZGgmvvkiC44gn+gix2KqWXHesT0OOfRWJ7jIjoucMC3KVnYX6ahhwg7YFieoxIScIpVYUK5oXbmRKNpCJw4MeM/ARJtysvaMD7ET0CDW85STsXzAunz7nISDdzxdFXdp8LKeVLVYrRpJr0MntqWr42BkKSd4WxsFlGvpouxLOwELqiiM9S2MkyggOlxacFEDX4dHNlbiIuOGTrCCUYNXU04SzCEEg87FWbKTkQG3ftEdog0F4dY6wSJEzB7Hg3dPbznDYYtDp6W1IHIyxXF1mJl20u0gq1VnjHNS2h1IOcH1HG44u3h1lL+1TnDbQCovux4EggW3uHjj6olME9Qno/CeJVgu0jlWg5WVc4wh1ZI3QtJOzDUoV/EGGtem8bY58ax4T4mtNUsTpYYzNoKhgRgu97JlWIE5j3MPFw9ds6QcC5fQUAq82BlKG2gtjct7SmYX1uBAnWY4Q09kq65hbXxPabhqteK+jlotcQAz3WktkJaTShd70MiNvuK2IXwRm0fTVsTUSRfv2Dq18ebudfDinT2b2dJvdbLtqgY9SyUIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIgiAIouL8Byl6MxEYyANXAAAAAElFTkSuQmCC"
                        alt="Company Logo" class="h-12 w-auto">
                </div>
                <div>
                    <h1 class="text-2xl font-bold">Your Company</h1>
                    <p class="text-blue-100">Inventory Management System</p>
                </div>
            </div>
            <div class="text-center">
                <p class="font-medium">Jl. Contoh No. 123</p>
                <p class="text-blue-100">Kota Bandung, Jawa Barat</p>
                <p class="text-blue-100">Indonesia 40123</p>
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
