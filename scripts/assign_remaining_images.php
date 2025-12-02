<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use App\Models\Event;

$files = Storage::disk('public')->files('events');
$used = [];
foreach(Event::all() as $e){ if($e->image) $used[] = $e->image; }

$available = array_values(array_filter($files, function($f) use ($used){ return !in_array($f, $used); }));
if(empty($available)){
    echo "No available image files to assign\n";
    exit;
}

$idx = 0;
foreach(Event::whereNull('image')->get() as $e){
    if(!isset($available[$idx])){
        echo "No more files for event {$e->id} - {$e->title}\n";
        continue;
    }
    $f = $available[$idx];
    $e->image = $f; // store path like 'events/xxx'
    $e->save();
    echo "Assigned {$f} to event {$e->id} - {$e->title}\n";
    $idx++;
}
