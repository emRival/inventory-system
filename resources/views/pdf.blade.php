
<!DOCTYPE html>
<html lang="id"> {{-- Mengganti lang ke "id" jika konten utama berbahasa Indonesia --}}

<head>
    <meta charset="UTF-8">
    <title>Kartu QR</title> {{-- Menambahkan title untuk dokumen --}}
    <style>
        @page {
            size: A4 portrait;
            margin: 10mm;
            /* Margin halaman */
        }

        body {
            margin: 0;
            padding: 0;
            font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif;
            /* Font lebih modern */
            color: #333;
            font-size: 8pt;
            /* Ukuran font default untuk body */
        }

        /* --- Grid Pengaturan --- */
        .cards-container {
            width: 100%;
            border-collapse: collapse;
        }

        .cards-container td.card-cell {
            width: 50%;
            /* 2 kolom */
            vertical-align: top;
            padding: 3mm;
            /* Jarak antar kartu */
        }

        /* Page break setelah N baris (misal, 6 baris x 2 kartu = 12 kartu per halaman) */
        /* Akan dikontrol oleh variabel $rowsPerPage dalam PHP */
        .cards-container tr.page-break-after {
            page-break-after: always;
        }

        /* --- Struktur Kartu --- */
        .card {
            border: 1px solid #999;
            background-color: #fdfdfd;
            /* Sedikit lebih cerah dari #fafafa */
            page-break-inside: avoid;
            /* Hindari kartu terpotong antar halaman */
            border-radius: 3px;
            /* Sedikit lengkungan pada sudut */
            overflow: hidden;
            /* Untuk memastikan border-radius bekerja dengan baik */
        }

        /* Header Kartu */
        .card-header {
            display: table;
            /* Menggunakan display table untuk layout sederhana */
            width: 100%;
            border-bottom: 1px solid #ccc;
            padding: 8px;
            /* Konsistensi padding */
            background-color: #e9ecef;
            /* Warna header lebih lembut */
            box-sizing: border-box;
        }

        .card-header .logo-container {
            display: table-cell;
            width: 20%;
            /* Atau sesuaikan dengan ukuran logo */
            vertical-align: middle;
            padding-right: 8px;
        }

        .card-header .logo-container img {
            width: 70px;
            /* Ukuran logo disesuaikan */
            height: 35px;
            display: block;
        }

        .card-header .title-container {
            display: table-cell;
            vertical-align: middle;
            font-size: 9pt;
            /* Sedikit lebih besar */
            font-weight: bold;
            color: #212529;
            /* Warna teks lebih gelap */
        }

        .card-header .subtitle {
            display: block;
            font-size: 7pt;
            font-weight: normal;
            /* Subtitle tidak perlu bold */
            color: #555;
            margin-top: 2px;
        }

        /* Body Kartu */
        .card-body {
            padding: 10px;
            /* Padding lebih lega */
            box-sizing: border-box;
        }

        .info-table {
            /* Mengganti nama dari inner-table */
            width: 100%;
            border-collapse: collapse;
        }

        .info-table td {
            padding: 3px 0;
            /* Mengurangi padding vertikal, hilangkan horizontal karena akan diatur di cell */
            vertical-align: top;
        }

        .qr-code-cell {
            width: 43%;
            /* Lebar kolom QR */
            text-align: center;
            padding-right: 8px;
            /* Jarak antara QR dan detail */
        }

        .qr-code-cell img.qr-image {
            width: 75px;
            /* Ukuran QR disesuaikan */
            height: 75px;
            border: 1px solid #ddd;
            /* Border lebih lembut */
            display: block;
            margin: 0 auto;
            /* Pusatkan QR */
        }

        .qr-code-placeholder {
            width: 75px;
            height: 75px;
            background-color: #f0f0f0;
            border: 1px dashed #ccc;
            display: flex;
            /* Untuk memusatkan teks placeholder */
            align-items: center;
            justify-content: center;
            margin: 0 auto;
            font-size: 7pt;
            color: #888;
        }

        .qr-id {
            /* Mengganti nama dari .id */
            font-size: 6.5pt;
            word-break: break-all;
            margin-top: 5px;
            color: #777;
        }

        .details-cell {
            width: 65%;
            /* Lebar kolom detail */
        }

        .details-table {
            width: 100%;
            border-collapse: collapse;
            font-size: 7.5pt;
        }

        .details-table td {
            padding: 3px 2px;
            /* Padding dalam tabel detail */
            vertical-align: top;
        }

        .details-table .label {
            width: 50%;
            /* Lebar kolom label */
            font-weight: bold;
            color: #444;
            padding-right: 5px;
        }

        .details-table .value {
            /* Kelas baru untuk value agar mudah ditarget jika perlu */
            width: 65%;
        }
    </style>
</head>

<body>

    @php
        // Konfigurasi Tata Letak Kartu
        $cardsPerRow = 2; // Jumlah kartu per baris
        $rowsPerPage = 10; // Jumlah baris per halaman
        $cardsPerPage = $cardsPerRow * $rowsPerPage; // Total kartu per halaman (12)

        // Chunk records berdasarkan jumlah kartu per halaman
        $pages = $records->chunk($cardsPerPage);

        $logoBase64 = base64_encode(file_get_contents(storage_path('app/public/logo/logo.png'))); // Placeholder logo minimalis
    @endphp

    <table class="cards-container">
        @foreach ($pages as $pageIndex => $pageRecords)
            @foreach ($pageRecords->chunk($cardsPerRow) as $rowIndex => $rowRecords)
                {{-- Tambahkan class untuk page break, kecuali ini adalah halaman terakhir DAN baris terakhir di halaman itu --}}
                <tr @if (($rowIndex + 1) % $rowsPerPage === 0 && !($loop->parent->last && $loop->last)) class="page-break-after" @endif>
                    @foreach ($rowRecords as $record)
                        @php
                            // Path ke file QR code
                            $qrPath = storage_path("app/public/qrs/{$record->qr_code}.png");
                            $qrBase64 = null;
                            if (file_exists($qrPath)) {
                                try {
                                    $qrBase64 = base64_encode(file_get_contents($qrPath));
                                } catch (\Exception $e) {
                                    // Handle error jika file tidak bisa dibaca, log error jika perlu
                                    // Log::error("Failed to read QR file: {$qrPath} - {$e->getMessage()}");
                                    $qrBase64 = null;
                                }
                            }
                        @endphp
                        <td class="card-cell">
                            <div class="card">
                                {{-- Header Kartu --}}
                                <div class="card-header">
                                    <div class="logo-container">
                                        {{-- Pastikan variabel $logoBase64 sudah di-pass dari controller atau didefinisikan di atas --}}
                                        <img src="data:image/png;base64,{{ $logoBase64 }}" alt="Logo Perusahaan">
                                    </div>
                                    <div class="title-container">
                                        DigiScan {{-- Nama Perusahaan/Aplikasi --}}
                                        <span class="subtitle">Jakarta Selatan</span> {{-- Sub-judul atau Lokasi --}}
                                    </div>
                                </div>

                                {{-- Body Kartu --}}
                                <div class="card-body">
                                    <table class="info-table">
                                        <tr>
                                            <td class="qr-code-cell">
                                                @if ($qrBase64)
                                                    <img src="data:image/png;base64,{{ $qrBase64 }}"
                                                        alt="QR Code {{ $record->qr_code }}" class="qr-image">
                                                @else
                                                    <div class="qr-code-placeholder">
                                                        <span>QR Tidak<br>Tersedia</span>
                                                    </div>
                                                @endif
                                                <div class="qr-id">{{ $record->qr_code }}</div>
                                            </td>
                                            <td class="details-cell">
                                                <table class="details-table">
                                                    <tr>
                                                        <td class="label">Kategori:</td>
                                                        <td class="value">
                                                            {{ $record->distribution->product->category->name ?? '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label">Produk:</td>
                                                        <td class="value">
                                                            {{ $record->distribution->product->name ?? '-' }}
                                                        </td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label">Distribusi Ke:</td>
                                                        <td class="value">
                                                            {{ $record->distribution->sector->name ?? '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label">Status:</td>
                                                        <td class="value">
                                                            {{ $record->status ? ucfirst($record->status) : '-' }}</td>
                                                    </tr>
                                                    <tr>
                                                        <td class="label">Kondisi:</td>
                                                        <td class="value">
                                                            {{ $record->condition ? ucfirst($record->condition) : '-' }}
                                                        </td>
                                                    </tr>
                                                    @if ($record->note)
                                                        <tr>
                                                            <td class="label">Catatan:</td>
                                                            <td class="value">{{ $record->note }}</td>
                                                        </tr>
                                                    @endif
                                                </table>
                                            </td>
                                        </tr>
                                    </table>
                                </div>
                            </div>
                        </td>
                    @endforeach

                    {{-- Tambah sel kosong jika jumlah kartu dalam baris ganjil (kurang dari $cardsPerRow) --}}
                    @if (count($rowRecords) < $cardsPerRow)
                        @for ($i = count($rowRecords); $i < $cardsPerRow; $i++)
                            <td class="card-cell"></td> {{-- Sel kosong untuk menjaga layout tabel --}}
                        @endfor
                    @endif
                </tr>
            @endforeach
        @endforeach
    </table>

</body>

</html>
