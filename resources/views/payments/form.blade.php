@extends('templates.app')

@section('content')
<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow-lg">
                <div class="card-header bg-primary text-white">
                    <h4 class="mb-0">
                        <i class="fas fa-credit-card"></i> Pembayaran Tiket
                    </h4>
                </div>
                <div class="card-body p-4">

                    {{-- Detail Event --}}
                    <div class="mb-4 p-3 bg-light rounded">
                        <div class="row align-items-center">
                            <div class="col-md-3">
                                @if($event->image)
                                    <img src="{{ asset('storage/' . $event->image) }}" alt="{{ $event->title }}"
                                         class="img-fluid rounded" style="height: 150px; object-fit: cover;">
                                @else
                                    <img src="https://via.placeholder.com/150" alt="No Image" class="img-fluid rounded">
                                @endif
                            </div>
                            <div class="col-md-9">
                                <h5 class="fw-bold">{{ $event->title }}</h5>
                                <p class="mb-1"><strong>Tanggal:</strong> {{ $event->start_date }}</p>
                                <p class="mb-1"><strong>Lokasi:</strong> {{ $event->location }}</p>
                                <p class="mb-0"><strong>Harga per Tiket:</strong> <span class="text-success fw-bold">Rp {{ number_format($event->harga ?? 100000, 0, ',', '.') }}</span></p>
                            </div>
                        </div>
                    </div>

                    {{-- Form Pembayaran --}}
                    <form action="{{ route('payment.store') }}" method="POST">
                        @csrf

                        <input type="hidden" name="event_id" value="{{ $event->id }}">

                        {{-- Quantity --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Jumlah Tiket</label>
                            <input type="number" name="quantity" class="form-control @error('quantity') is-invalid @enderror"
                                   value="{{ old('quantity', 1) }}" min="1" max="10" required>
                            @error('quantity')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Total Price Display --}}
                        <div class="mb-3 p-3 bg-info bg-opacity-10 rounded">
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-0">Jumlah Tiket:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-0" id="display-qty">1</p>
                                </div>
                            </div>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-0">Harga per Tiket:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-0">Rp {{ number_format($event->harga ?? 100000, 0, ',', '.') }}</p>
                                </div>
                            </div>
                            <hr>
                            <div class="row">
                                <div class="col-6">
                                    <p class="mb-0 fw-bold">Total:</p>
                                </div>
                                <div class="col-6 text-end">
                                    <p class="mb-0 fw-bold text-success" id="display-total">Rp {{ number_format($event->harga ?? 100000, 0, ',', '.') }}</p>
                                </div>
                            </div>
                        </div>

                        {{-- Payment Method --}}
                        <div class="mb-3">
                            <label class="form-label fw-bold">Metode Pembayaran</label>
                            <div class="d-flex gap-3">
                                <div class="form-check">
                                    <input class="form-check-input payment-method" type="radio" name="payment_method"
                                           id="credit_card" value="credit_card"
                                           {{ old('payment_method') == 'credit_card' ? 'checked' : '' }} required>
                                    <label class="form-check-label" for="credit_card">
                                        <i class="fas fa-credit-card"></i> Kartu Kredit
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input payment-method" type="radio" name="payment_method"
                                           id="bank_transfer" value="bank_transfer" required>
                                    <label class="form-check-label" for="bank_transfer">
                                        <i class="fas fa-university"></i> Transfer Bank
                                    </label>
                                </div>
                                <div class="form-check">
                                    <input class="form-check-input payment-method" type="radio" name="payment_method"
                                           id="e_wallet" value="e_wallet" required>
                                    <label class="form-check-label" for="e_wallet">
                                        <i class="fas fa-wallet"></i> E-Wallet
                                    </label>
                                </div>
                            </div>
                            @error('payment_method')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Credit Card Section --}}
                        <div id="credit-card-section" class="mb-3" style="display: none;">
                            <div class="card border-info">
                                <div class="card-body">
                                    {{-- Card Number --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nomor Kartu</label>
                                        <input type="text" name="card_number" class="form-control @error('card_number') is-invalid @enderror"
                                               placeholder="1234 5678 9012 3456" maxlength="19" inputmode="numeric"
                                               value="{{ old('card_number') }}">
                                        <small class="form-text text-muted">16 digit tanpa spasi</small>
                                        @error('card_number')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    {{-- Card Name --}}
                                    <div class="mb-3">
                                        <label class="form-label">Nama di Kartu</label>
                                        <input type="text" name="card_name" class="form-control @error('card_name') is-invalid @enderror"
                                               placeholder="NAMA PEMILIK KARTU" value="{{ old('card_name') }}" style="text-transform: uppercase;">
                                        <small class="form-text text-muted">Sesuai nama di kartu kredit</small>
                                        @error('card_name')
                                            <div class="invalid-feedback d-block">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row">
                                        {{-- Exp Date --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">Tanggal Kadaluarsa</label>
                                            <input type="text" name="card_exp" class="form-control @error('card_exp') is-invalid @enderror"
                                                   placeholder="12/25" maxlength="5" inputmode="numeric"
                                                   value="{{ old('card_exp') }}">
                                            <small class="form-text text-muted">Format: MM/YY (contoh: 12/25)</small>
                                            @error('card_exp')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>

                                        {{-- CVV --}}
                                        <div class="col-md-6 mb-3">
                                            <label class="form-label">CVV</label>
                                            <input type="password" name="card_cvv" class="form-control @error('card_cvv') is-invalid @enderror"
                                                   placeholder="123" maxlength="3" inputmode="numeric"
                                                   value="{{ old('card_cvv') }}">
                                            <small class="form-text text-muted">3 digit di belakang kartu</small>
                                            @error('card_cvv')
                                                <div class="invalid-feedback d-block">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="alert alert-warning" role="alert">
                                        <small>
                                            <i class="fas fa-info-circle"></i>
                                            <strong>Demo Mode:</strong> Gunakan nomor kartu test: 4532 0151 9872 7932 (16 digit, hilangkan spasi)
                                        </small>
                                    </div>
                                </div>
                            </div>
                        </div>                        {{-- Bank Transfer Section --}}
                        <div id="bank-transfer-section" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Instruksi Transfer Bank</h6>
                                <p class="mb-1"><strong>Bank Tujuan:</strong> BCA</p>
                                <p class="mb-1"><strong>Nomor Rekening:</strong> 1234567890</p>
                                <p class="mb-0"><strong>Atas Nama:</strong> PT. Seni Julpian</p>
                                <hr>
                                <p class="mb-0 text-muted">Transfer akan dikonfirmasi secara otomatis dalam 5-10 menit.</p>
                            </div>
                        </div>

                        {{-- E-Wallet Section --}}
                        <div id="e-wallet-section" class="mb-3" style="display: none;">
                            <div class="alert alert-info">
                                <h6 class="alert-heading">Pembayaran E-Wallet</h6>
                                <p class="mb-2">Pilih metode e-wallet favorit Anda:</p>
                                <div class="d-flex gap-2">
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fab fa-cc-paypal"></i> GCash
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-wallet"></i> OVO
                                    </button>
                                    <button type="button" class="btn btn-sm btn-outline-primary">
                                        <i class="fas fa-wallet"></i> DANA
                                    </button>
                                </div>
                            </div>
                        </div>

                        {{-- Terms and Conditions --}}
                        <div class="mb-3 form-check">
                            <input type="checkbox" class="form-check-input" id="terms" name="terms"
                                   {{ old('terms') ? 'checked' : '' }} required>
                            <label class="form-check-label" for="terms">
                                Saya setuju dengan <a href="#" target="_blank">syarat dan ketentuan</a>
                            </label>
                            @error('terms')
                                <div class="invalid-feedback d-block">{{ $message }}</div>
                            @enderror
                        </div>

                        {{-- Buttons --}}
                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg fw-bold">
                                <i class="fas fa-lock"></i> Lanjutkan Pembayaran
                            </button>
                            <a href="{{ route('events.detail', $event->id) }}" class="btn btn-outline-secondary">
                                Kembali ke Event
                            </a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    const quantityInput = document.querySelector('input[name="quantity"]');
    const displayQty = document.getElementById('display-qty');
    const displayTotal = document.getElementById('display-total');

    // Update total saat quantity berubah
    quantityInput.addEventListener('change', function() {
        const qty = parseInt(this.value) || 1;
        const price = {{ $event->harga }};
        const total = qty * price;

        displayQty.textContent = qty;
        displayTotal.textContent = 'Rp ' + total.toLocaleString('id-ID');
    });

    // Tampilkan/sembunyikan payment method section
    document.querySelectorAll('.payment-method').forEach(radio => {
        radio.addEventListener('change', function() {
            document.getElementById('credit-card-section').style.display = 'none';
            document.getElementById('bank-transfer-section').style.display = 'none';
            document.getElementById('e-wallet-section').style.display = 'none';

            if (this.value === 'credit_card') {
                document.getElementById('credit-card-section').style.display = 'block';
            } else if (this.value === 'bank_transfer') {
                document.getElementById('bank-transfer-section').style.display = 'block';
            } else if (this.value === 'e_wallet') {
                document.getElementById('e-wallet-section').style.display = 'block';
            }
        });
    });

    // Set initial payment method
    const checkedMethod = document.querySelector('.payment-method:checked');
    if (checkedMethod && checkedMethod.value === 'credit_card') {
        document.getElementById('credit-card-section').style.display = 'block';
    }
</script>
@endsection
