<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Event;

foreach(Event::all() as $e){
    $norm = strtolower(preg_replace('/[^a-z0-9]/','', $e->slug));
    echo "{$e->id} - slug: {$e->slug} - norm: {$norm} - title: {$e->title}\n";
}
