<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" class="h-full">
<head>
    @vite(['resources/css/app.css', 'resources/js/app.js']) {{-- atau sesuai dengan asset kamu --}}
    @filamentStyles
    @livewireStyles
</head>
<body class="antialiased bg-gray-100 text-gray-900">
    {{ $slot }}

    @livewireScripts
    @filamentScripts

    <script>
        // 🔒 Cek lokasi dan paksa allow
        function requestLocationUntilGranted() {
            navigator.geolocation.getCurrentPosition(
                function(position) {
                    console.log("Lokasi diizinkan:", position);
                },
                function(error) {
                    if (error.code === error.PERMISSION_DENIED) {
                        alert("Mohon izinkan akses lokasi untuk melanjutkan!");
                        setTimeout(requestLocationUntilGranted, 3000); // Coba lagi
                    }
                }
            );
        }
        requestLocationUntilGranted();
    </script>
</body>
</html>
