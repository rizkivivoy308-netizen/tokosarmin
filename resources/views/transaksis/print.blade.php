<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Nota #{{ $transaksi->id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier New', monospace;
            font-size: 12px;
            background: #f0f0f0;
            display: flex;
            justify-content: center;
            padding: 20px;
        }

        /* ── Wrapper Nota ── */
        .nota-wrapper {
            background: white;
            width: 80mm; /* lebar kertas thermal */
            padding: 10px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }

        /* ── Header Toko ── */
        .header {
            text-align: center;
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .header h1 {
            font-size: 16px;
            font-weight: bold;
            letter-spacing: 2px;
        }

        .header p {
            font-size: 10px;
            color: #555;
            margin-top: 2px;
        }

        /* ── Info Transaksi ── */
        .info-transaksi {
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .info-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 2px;
            font-size: 11px;
        }

        .info-row span:first-child {
            color: #555;
        }

        /* ── Tabel Barang ── */
        .items {
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .item-header {
            display: flex;
            justify-content: space-between;
            font-weight: bold;
            font-size: 10px;
            border-bottom: 1px solid #000;
            padding-bottom: 3px;
            margin-bottom: 5px;
        }

        .item-row {
            margin-bottom: 5px;
        }

        .item-name {
            font-weight: bold;
            font-size: 11px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            font-size: 10px;
            color: #555;
            margin-top: 1px;
        }

        /* ── Total ── */
        .total-section {
            border-bottom: 1px dashed #000;
            padding-bottom: 8px;
            margin-bottom: 8px;
        }

        .total-row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 3px;
            font-size: 11px;
        }

        .total-row.grand-total {
            font-weight: bold;
            font-size: 13px;
            border-top: 1px solid #000;
            padding-top: 5px;
            margin-top: 3px;
        }

        .total-row.hutang {
            color: #cc0000;
            font-weight: bold;
        }

        /* ── Status Hutang ── */
        .status-hutang {
            text-align: center;
            border: 1px dashed #cc0000;
            padding: 5px;
            margin-bottom: 8px;
            color: #cc0000;
            font-weight: bold;
            font-size: 11px;
        }

        .status-lunas {
            text-align: center;
            border: 1px dashed #007700;
            padding: 5px;
            margin-bottom: 8px;
            color: #007700;
            font-weight: bold;
            font-size: 11px;
        }

        /* ── Footer ── */
        .footer {
            text-align: center;
            font-size: 10px;
            color: #555;
            line-height: 1.6;
        }

        .footer .thank-you {
            font-size: 12px;
            font-weight: bold;
            color: #000;
            margin-bottom: 3px;
        }

        /* ── Tombol Print (tidak ikut tercetak) ── */
        .btn-print-area {
            text-align: center;
            margin-top: 20px;
        }

        .btn-print {
            background: #1a237e;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            margin-right: 10px;
        }

        .btn-back {
            background: #666;
            color: white;
            border: none;
            padding: 10px 30px;
            border-radius: 5px;
            cursor: pointer;
            font-size: 14px;
            text-decoration: none;
            display: inline-block;
        }

        /* ── Saat Print — sembunyikan tombol ── */
        @media print {
            body {
                background: white;
                padding: 0;
            }

            .nota-wrapper {
                box-shadow: none;
                width: 100%;
            }

            .btn-print-area {
                display: none !important;
            }

            /* Potong halaman jika panjang */
            @page {
                margin: 5mm;
                size: 80mm auto;
            }
        }
    </style>
</head>
<body>

<div>
    {{-- ══════════════════════
         NOTA STRUK
    ══════════════════════ --}}
    <div class="nota-wrapper" id="nota">

        {{-- Header Toko --}}
        <div class="header">
            <h1>TOKO SARMIN</h1>
            <p>Jl.segara anakan No. , patimuan</p>
            <p>Telp: 0812-3456-7890</p>
            <p>{{ \Carbon\Carbon::now()->format('d/m/Y H:i') }}</p>
        </div>

        {{-- Info Transaksi --}}
        <div class="info-transaksi">
            <div class="info-row">
                <span>No. Nota</span>
                <span>#{{ str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            <div class="info-row">
                <span>Tanggal</span>
                <span>{{ \Carbon\Carbon::parse($transaksi->tanggal)->format('d/m/Y') }}</span>
            </div>
            <div class="info-row">
                <span>Pelanggan</span>
                <span>{{ $transaksi->customer->nama }}</span>
            </div>
            <div class="info-row">
                <span>Pembayaran</span>
                <span>{{ ucfirst($transaksi->metode_pembayaran) }}</span>
            </div>
        </div>

        {{-- Daftar Barang --}}
        <div class="items">
            <div class="item-header">
                <span>BARANG</span>
                <span>SUBTOTAL</span>
            </div>

            @foreach($transaksi->detailTransaksis as $detail)
            <div class="item-row">
                <div class="item-name">{{ $detail->barang->nama_barang }}</div>
                <div class="item-detail">
                    <span>{{ $detail->jumlah }} x Rp {{ number_format($detail->barang->harga, 0, ',', '.') }}</span>
                    <span>Rp {{ number_format($detail->subtotal, 0, ',', '.') }}</span>
                </div>
            </div>
            @endforeach
        </div>

        {{-- Total --}}
        <div class="total-section">
            <div class="total-row grand-total">
                <span>TOTAL</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>

            @if($transaksi->metode_pembayaran === 'cash')
            <div class="total-row">
                <span>Tunai</span>
                <span>Rp {{ number_format($transaksi->total, 0, ',', '.') }}</span>
            </div>
            <div class="total-row">
                <span>Kembali</span>
                <span>Rp 0</span>
            </div>
            @endif

            @if($transaksi->hutang)
            <div class="total-row hutang">
                <span>Hutang</span>
                <span>Rp {{ number_format($transaksi->hutang->sisa_hutang, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>

        {{-- Status Hutang --}}
        @if($transaksi->hutang)
            @if($transaksi->hutang->status === 'belum')
            <div class="status-hutang">
                ⚠ HUTANG — Jatuh Tempo:<br>
                {{ \Carbon\Carbon::parse($transaksi->hutang->jatuh_tempo)->format('d/m/Y') }}
            </div>
            @else
            <div class="status-lunas">
                ✓ LUNAS
            </div>
            @endif
        @endif

        {{-- Footer --}}
        <div class="footer">
            <div class="thank-you">Terima Kasih!</div>
            <p>Barang yang sudah dibeli</p>
            <p>tidak dapat dikembalikan</p>
            <p>— Simpan nota ini —</p>
        </div>

    </div>

    {{-- Tombol Print & Kembali (tidak ikut tercetak) --}}
    <div class="btn-print-area">
        <button class="btn-print" onclick="window.print()">
            🖨️ Print Nota
        </button>
        <a href="{{ route('transaksis.show', $transaksi) }}" class="btn-back">
            ← Kembali
        </a>
    </div>
</div>

<script>
    // Auto print saat halaman dibuka (opsional)
    // window.onload = function() { window.print(); }
</script>

</body>
</html>