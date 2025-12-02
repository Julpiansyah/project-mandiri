<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Event;

$files = Storage::disk('public')->files('events');
if(empty($files)){
    echo "No files found in storage/events\n";
    exit;
}

foreach($files as $f){
    $name = pathinfo($f, PATHINFO_FILENAME);
    $slug = Str::slug($name);
    $event = Event::where('slug', $slug)->first();
    if($event){
        $event->image = 'events/'.basename($f);
        $event->save();
        echo "Updated {$event->id} -> {$event->image}\n";
    } else {
        echo "No match for {$f}\n";
    }
}
