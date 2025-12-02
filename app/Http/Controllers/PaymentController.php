<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class PaymentController extends Controller
{
    /**
     * Tampilkan form pembayaran
     */
    public function create($event_id)
    {
        $event = Event::findOrFail($event_id);

        if (!Auth::check()) {
            return redirect()->route('login')->with('error', 'Anda harus login terlebih dahulu');
        }

        return view('payments.form', compact('event'));
    }

    /**
     * Proses pembayaran
     */
    public function store(Request $request)
    {
        $rules = [
            'event_id' => 'required|exists:events,id',
            'quantity' => 'required|integer|min:1|max:10',
            'payment_method' => 'required|in:credit_card,bank_transfer,e_wallet',
            'terms' => 'accepted',
        ];

        // Validasi conditional untuk credit card - tapi dibuat optional untuk demo
        if ($request->payment_method === 'credit_card' && $request->filled('card_number')) {
            $rules['card_number'] = 'digits:16';
            $rules['card_name'] = 'string|min:3';
            $rules['card_cvv'] = 'digits:3';
            // card_exp format: MM/YY atau MM/YYYY
            $rules['card_exp'] = 'regex:/^\d{2}\/(20)?\d{2}$/';
        }

        $validated = $request->validate($rules, [
            'card_exp.regex' => 'Format tanggal kadaluarsa harus MM/YY (contoh: 12/25)',
            'quantity.max' => 'Maksimal pembelian 10 tiket sekaligus',
        ]);

        $event = Event::findOrFail($request->event_id);
        $user = Auth::user();

        // Hitung total harga
        $unit_price = $event->harga ?? 100000;
        $total_price = $unit_price * $request->quantity;

        // Generate transaction ID yang unik
        $transaction_id = 'TRX-' . strtoupper(Str::random(8)) . '-' . date('YmdHis');

        // Buat record payment
        $payment = Payment::create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'quantity' => $request->quantity,
            'unit_price' => $unit_price,
            'total_price' => $total_price,
            'status' => 'completed',
            'payment_method' => $request->payment_method,
            'transaction_id' => $transaction_id,
            'paid_at' => now(),
            'notes' => "Pembelian {$request->quantity} tiket untuk event: {$event->title}"
        ]);

        return redirect()->route('payment.success', $payment->id)->with('success', 'Pembayaran berhasil!');
    }

    /**
     * Halaman sukses pembayaran
     */
    public function success($payment_id)
    {
        $payment = Payment::with(['user', 'event'])->findOrFail($payment_id);

        // Hanya owner payment atau admin bisa lihat
        if (Auth::user()->id !== $payment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('payments.success', compact('payment'));
    }

    /**
     * Halaman history pembayaran
     */
    public function history()
    {
        $payments = Auth::user()->payments()->with('event')->latest()->get();
        return view('payments.history', compact('payments'));
    }

    /**
     * Detail pembayaran
     */
    public function show($payment_id)
    {
        $payment = Payment::with(['user', 'event'])->findOrFail($payment_id);

        if (Auth::user()->id !== $payment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('payments.detail', compact('payment'));
    }
}
