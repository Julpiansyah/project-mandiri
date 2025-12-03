<?php

namespace App\Exports;

use App\Models\Payment;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Carbon\Carbon;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
     * @return \Illuminate\Support\Collection
     */
    public function collection()
    {
        return Payment::with(['user','event'])->orderByDesc('created_at')->get();
    }

    /**
     * Map data for each row
     */
    public function map($payment): array
    {
        $userName = $payment->user->name ?? '-';
        $userEmail = $payment->user->email ?? '-';
        $eventTitle = $payment->event->title ?? '-';

        $now = Carbon::now();
        $eventEnded = false;
        if ($payment->event) {
            if ($payment->event->end_date) {
                $eventEnded = Carbon::parse($payment->event->end_date)->lt($now);
            } else {
                $eventEnded = Carbon::parse($payment->event->start_date)->lt($now);
            }
        }

        if ($eventEnded) {
            $status = 'Kadaluarsa';
        } else {
            $status = match($payment->status) {
                'completed' => 'Selesai',
                'pending' => 'Menunggu',
                default => ucfirst($payment->status),
            };
        }

        return [
            $payment->transaction_id,
            $userName,
            $userEmail,
            $eventTitle,
            $payment->quantity,
            number_format($payment->total_price, 0, ',', '.'),
            str_replace('_', ' ', $payment->payment_method ?? '-'),
            $status,
            $payment->created_at?->format('Y-m-d H:i:s'),
        ];
    }

    public function headings(): array
    {
        return [
            'Transaction ID',
            'User Name',
            'User Email',
            'Event',
            'Quantity',
            'Total Price',
            'Payment Method',
            'Status',
            'Created At',
        ];
    }
}
