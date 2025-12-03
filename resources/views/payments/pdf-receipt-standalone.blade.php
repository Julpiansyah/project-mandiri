<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bukti Pembayaran - {{ $payment->transaction_id }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        html, body {
            background: white;
            color: #2c3e50;
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', 'Roboto', 'Oxygen', 'Ubuntu', 'Cantarell', sans-serif;
            line-height: 1.6;
        }

        .receipt-wrapper {
            max-width: 900px;
            margin: 0 auto;
            background: white;
            padding: 30px;
        }

        .receipt-card {
            background: white;
            overflow: hidden;
            border: 1px solid #e8f4f8;
        }

        /* Header biru GOERS */
        .receipt-header {
            background: linear-gradient(135deg, #0097a7 0%, #00bcd4 100%);
            color: white;
            padding: 45px 40px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 151, 167, 0.15);
        }

        .receipt-header h1 {
            font-size: 3.2rem;
            font-weight: 900;
            margin: 0 0 8px 0;
            letter-spacing: 5px;
            text-shadow: none;
            color: #ffffff;
        }

        .receipt-header p.subtitle {
            margin: 0;
            font-size: 1rem;
            opacity: 1;
            letter-spacing: 0.5px;
            color: #ffffff;
        }

        .receipt-body {
            padding: 45px 40px;
            background: #fafbfc;
        }

        /* Divider */
        .divider {
            height: 1px;
            background: linear-gradient(90deg, transparent, #d4e8ed, transparent);
            margin: 30px 0;
        }

        /* ID Transaksi */
        .transaction-id {
            text-align: center;
            margin: 0 auto 35px;
            background: linear-gradient(135deg, #e3f2fd 0%, #bbdefb 100%);
            border: 2px solid #0097a7;
            color: #0097a7;
            padding: 22px;
            border-radius: 8px;
            font-weight: 700;
            letter-spacing: 2px;
            font-size: 1.5rem;
            font-family: 'Courier New', monospace;
        }

        /* Section title */
        .section-title {
            font-size: 0.95rem;
            font-weight: 800;
            color: #000000;
            text-transform: uppercase;
            letter-spacing: 2px;
            margin: 35px 0 18px 0;
            padding-bottom: 10px;
            border-bottom: 3px solid #00bcd4;
            display: inline-block;
        }

        /* Info grid */
        .info-grid {
            display: grid;
            grid-template-columns: 160px 1fr;
            gap: 14px 25px;
            margin-bottom: 0;
            font-size: 0.98rem;
            background: white;
            padding: 20px;
            border-radius: 6px;
            border-left: 4px solid #00bcd4;
        }

        .info-label {
            font-weight: 700;
            color: #000000;
            text-align: left;
        }

        .info-value {
            color: #000000;
            font-weight: 600;
            word-break: break-word;
        }

        /* Status badge */
        .status-badge {
            display: inline-block;
            padding: 7px 14px;
            border-radius: 20px;
            font-size: 0.8rem;
            font-weight: 700;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .status-active {
            background: #c8e6c9;
            color: #1b5e20;
        }

        .status-expired {
            background: #ffcdd2;
            color: #b71c1c;
        }

        /* Payment summary */
        .payment-summary {
            background: linear-gradient(135deg, #004d40 0%, #00695c 100%);
            color: #ffffff;
            padding: 32px;
            margin: 35px 0;
            text-align: center;
            border-radius: 6px;
            box-shadow: 0 3px 12px rgba(0, 151, 167, 0.2);
        }

        .payment-summary .row {
            display: flex;
            justify-content: space-between;
            margin-bottom: 16px;
            font-size: 1rem;
            padding: 8px 0;
            color: #000000;
        }

        .payment-summary .total {
            font-size: 2.2rem;
            font-weight: 900;
            margin-top: 22px;
            padding-top: 22px;
            border-top: 2px dashed rgba(0, 0, 0, 0.3);
            letter-spacing: 0.5px;
            color: #000000;
        }

        .payment-summary .total-label {
            font-size: 0.95rem;
            opacity: 1;
            margin-bottom: 8px;
            font-weight: 600;
            color: #000000;
        }

        /* Footer */
        .receipt-footer {
            text-align: center;
            padding: 25px;
            background: #e0f7fa;
            color: #000000;
            font-size: 0.93rem;
            border-top: 2px solid #b2ebf2;
            margin-top: 30px;
            border-radius: 0 0 6px 6px;
        }

        .receipt-footer p {
            margin: 4px 0;
            line-height: 1.5;
        }

        .receipt-footer strong {
            color: #000000;
        }

        /* Print styles */
        @media print {
            html, body {
                margin: 0;
                padding: 0;
                background: white;
                width: 100%;
                height: 100%;
            }

            .receipt-wrapper {
                margin: 0;
                padding: 20px;
                max-width: 100%;
            }

            .receipt-card {
                border: none;
            }

            .receipt-body {
                padding: 40px 30px;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-wrapper">
        <div class="receipt-card">

            <!-- Header -->
            <div class="receipt-header">
                <h1>GOERS</h1>
                <p class="subtitle">Bukti Pembayaran Tiket Event</p>
            </div>

            <div class="receipt-body">

                <!-- ID Transaksi -->
                <div class="transaction-id">
                    {{ $payment->transaction_id }}
                </div>

                <!-- Info Pembelian -->
                <div class="section-title">Informasi Transaksi</div>
                <div class="info-grid">
                    <div class="info-label">Tanggal Pembelian</div>
                    <div class="info-value">{{ $payment->created_at->format('d F Y, H:i') }} WIB</div>

                    <div class="info-label">Status Pembayaran</div>
                    <div class="info-value">
                        @if($payment->is_expired)
                            <span class="status-badge status-expired">Kadaluarsa</span>
                        @else
                            <span class="status-badge status-active">Berhasil</span>
                        @endif
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Buyer Info -->
                <div class="section-title">Informasi Pembeli</div>
                <div class="info-grid">
                    <div class="info-label">Nama Lengkap</div>
                    <div class="info-value">{{ $payment->user->name }}</div>
                    <div class="info-label">Email</div>
                    <div class="info-value">{{ $payment->user->email }}</div>
                </div>

                <div class="divider"></div>

                <!-- Event Info -->
                <div class="section-title">Detail Event</div>
                <div class="info-grid">
                    <div class="info-label">Nama Event</div>
                    <div class="info-value">{{ $payment->event->title }}</div>
                    <div class="info-label">Lokasi</div>
                    <div class="info-value">{{ $payment->event->location ?? '-' }}</div>
                    <div class="info-label">Tanggal Event</div>
                    <div class="info-value">{{ \Carbon\Carbon::parse($payment->event->start_date)->format('d F Y') }}</div>
                    @if($payment->event->artis)
                    <div class="info-label">Artis / Performer</div>
                    <div class="info-value">{{ $payment->event->artis }}</div>
                    @endif
                </div>

                <div class="divider"></div>

                <!-- Payment Summary -->
                <div class="section-title">Ringkasan Pembayaran</div>
                <div class="payment-summary">
                    <div class="row">
                        <span>Harga per Tiket</span>
                        <span style="font-weight: 600; color: #000000;">Rp {{ number_format($payment->unit_price, 0, ',', '.') }}</span>
                    </div>
                    <div class="row">
                        <span>Jumlah Tiket</span>
                        <span style="font-weight: 600; color: #000000;">{{ $payment->quantity }} × Tiket</span>
                    </div>
                    <div class="total">
                        <div class="total-label">Total Pembayaran</div>
                        <div style="color: #000000;">Rp {{ number_format($payment->total_price, 0, ',', '.') }}</div>
                    </div>
                </div>

                <div class="divider"></div>

                <!-- Payment Method -->
                <div class="section-title">Metode Pembayaran</div>
                <div class="info-grid">
                    <div class="info-label">Metode</div>
                    <div class="info-value">
                        @switch($payment->payment_method)
                            @case('credit_card')
                                Kartu Kredit
                            @break
                            @case('bank_transfer')
                                Transfer Bank
                            @break
                            @case('e_wallet')
                                E-Wallet
                            @break
                            @default
                                {{ ucfirst(str_replace('_', ' ', $payment->payment_method)) }}
                        @endswitch
                    </div>
                </div>

                <div class="receipt-footer">
                    <p><strong>Terima kasih telah membeli tiket di GOERS</strong></p>
                    <p>Bukti pembayaran ini adalah dokumen resmi. Harap disimpan dengan baik.</p>
                </div>

            </div>
        </div>
    </div>
</body>
</html>
