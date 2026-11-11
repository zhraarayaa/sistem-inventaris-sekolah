<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Peminjaman Inventaris Sekolah</title>
    <style>
        body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; font-size: 11px; color: #333; margin: 30px; }
        
        /* Kop Surat / Header Laporan */
        .header { text-align: center; border-bottom: 3px double #333; padding-bottom: 12px; margin-bottom: 25px; }
        .header h2 { margin: 0; text-transform: uppercase; font-size: 15px; font-weight: bold; letter-spacing: 0.5px; }
        .header h3 { margin: 4px 0; text-transform: uppercase; font-size: 13px; font-weight: normal; color: #555; }
        .header p { margin: 4px 0; color: #666; font-size: 10px; }

        /* Tabel Data */
        table { width: 100%; border-collapse: collapse; margin-top: 10px; margin-bottom: 30px; }
        th, td { border: 1px solid #bbb; padding: 7px 10px; text-align: left; vertical-align: middle; }
        th { background-color: #f1f5f9; font-weight: bold; text-transform: uppercase; font-size: 9.5px; color: #1e293b; letter-spacing: 0.3px; }
        td { font-size: 10.5px; color: #27272a; }
        
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        
        /* Bagian Tanda Tangan */
        .signature-section { float: right; width: 220px; text-align: center; margin-top: 20px; font-size: 11px; }
        .signature-space { height: 60px; }
        .signature-name { font-weight: bold; text-decoration: underline; }

        /* Tombol Cetak (Hilang saat diprint) */
        @media print {
            .no-print { display: none; }
            body { margin: 0; }
        }
    </style>
</head>
<body onload="window.print()">

    <!-- Tombol Cetak / Simpan PDF interaktif -->
    <div class="no-print" style="margin-bottom: 20px; text-align: right; background: #f8fafc; padding: 10px; border-radius: 8px; border: 1px solid #e2e8f0;">
        <button onclick="window.print()" style="padding: 8px 18px; background: #2563eb; color: #fff; border: none; border-radius: 6px; cursor: pointer; font-weight: bold; font-size: 12px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);">🖨️ Cetak / Simpan sebagai PDF</button>
    </div>

    <!-- Kop Laporan -->
    <div class="header">
        <h3>Sistem Inventaris Sekolah</h3>
        <h2>Laporan Rekapitulasi Peminjaman Barang</h2>
        <p>Periode Rekap: Keseluruhan Data &nbsp;|&nbsp; Dicetak pada: {{ date('d-m-Y H:i') }}</p>
    </div>

    <!-- Tabel Data -->
    <table>
        <thead>
            <tr>
                <th style="width: 30px;" class="text-center">#</th>
                <th>Nama Peminjam</th>
                <th>Nama Barang</th>
                <th class="text-center" style="width: 45px;">Qty</th>
                <th>Tanggal Pinjam</th>
                <th>Jatuh Tempo</th>
                <th class="text-center">Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse($loans as $index => $loan)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td><strong>{{ $loan->user->name ?? '-' }}</strong></td>
                    <td>{{ $loan->item->name ?? '-' }}</td>
                    <td class="text-center"><strong>{{ $loan->quantity }}</strong></td>
                    <td>{{ \Carbon\Carbon::parse($loan->borrow_date ?? $loan->loan_date)->format('d/m/Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($loan->due_date)->format('d/m/Y') }}</td>
                    <td class="text-center">
                        <span style="font-weight: bold; font-size: 9px; text-transform: uppercase;">
                            {{ $loan->status }}
                        </span>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="color: #999; padding: 25px;">Tidak ada data peminjaman yang tersedia.</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Tanda Tangan Pengelola / Admin -->
    <div class="signature-section">
        <p>Ponorogo, {{ date('d F Y') }}</p>
        <p>Mengetahui,<br><strong>Administrator Inventaris</strong></p>
        <div class="signature-space"></div>
        <p class="signature-name">{{ auth()->user()->name ?? 'Admin Sekolah' }}</p>
    </div>

</body>
</html>