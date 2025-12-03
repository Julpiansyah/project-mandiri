<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Payment;
use Carbon\Carbon;

$payments = Payment::with('event','user')->limit(50)->get();
if($payments->isEmpty()){
    echo "No payments found\n";
    exit;
}

foreach($payments as $p){
    $event = $p->event;
    $start = $event->start_date ?? 'NULL';
    $end = $event->end_date ?? 'NULL';
    $today = Carbon::today()->toDateString();
    $dateToCheck = $end ?? $start;
    $eventDate = null;
    try{ if($dateToCheck) $eventDate = Carbon::parse($dateToCheck)->toDateString(); } catch(Exception $e) { $eventDate = 'INVALID'; }
    $isExpired = ($eventDate !== null && $eventDate !== 'INVALID') ? ($eventDate < $today) : false;
    echo "Payment ID: {$p->id} | Transaction: {$p->transaction_id} | User: " . ($p->user->email ?? '-') . "\n";
    echo "  Event ID: " . ($event->id ?? '-') . " | start: {$start} | end: {$end} | dateToCheck: {$dateToCheck} | eventDate: {$eventDate} | today: {$today} | isExpired: " . ($isExpired? 'true':'false') . "\n\n";
}
