<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Event;
use App\Models\Payment;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PaymentsExport;
use Barryvdh\DomPDF\Facade\Pdf;


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

        // Buat record payment dan tersimpan d dta base
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
        // Ambil semua payment milik user beserta event terkait
        $payments = Payment::with('event')->where('user_id', Auth::user()->id)->get();

        // is_expired sekarang di-handle oleh accessor di model Payment
        return view('payments.history', compact('payments'));
    }

    /**
     * Admin: lihat semua transaksi (table)
     */
    public function adminIndex()
    {
        // Ambil semua payment dengan relasi user+event yang masih ada
        $payments = Payment::with(['user','event'])->whereHas('user')->whereHas('event')->orderByDesc('created_at')->paginate(50);

        return view('admin.payments.index', compact('payments'));
    }

    /**
     * Admin export payments to Excel
     */
    public function exportAdmin()
    {
        return Excel::download(new PaymentsExport, 'riwayat-transaksi-pengguna.xlsx');
    }

    /**
     * User export payments to Excel
     */
    public function export()
    {
        return Excel::download(new PaymentsExport, 'payments.xlsx');
    }

    /**
     * Detail pembayaran
     */
    //menampilkan detail stu pembyran 
    public function show($payment_id)
    {
        $payment = Payment::with(['user', 'event'])->findOrFail($payment_id);

        if (Auth::user()->id !== $payment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        return view('payments.detail', compact('payment'));
    }

    /**
     * Download payment receipt as PDF
     */
    public function downloadPDF($payment_id)
    {
        $payment = Payment::with(['user', 'event'])->findOrFail($payment_id);

        if (Auth::user()->id !== $payment->user_id && Auth::user()->role !== 'admin') {
            abort(403, 'Unauthorized');
        }

        $pdf = Pdf::loadView('payments.pdf-receipt-standalone', compact('payment'));
        return $pdf->download('bukti-pembayaran-' . $payment->transaction_id . '.pdf');
    }
}
