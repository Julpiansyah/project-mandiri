<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use App\Models\Event;

$files = Storage::disk('public')->files('events');

foreach($files as $f){
    $name = pathinfo($f, PATHINFO_FILENAME);
    $slug1 = Str::slug($name); // e.g. theadams
    $normName = strtolower(preg_replace('/[^a-z0-9]/','', $name));

    $event = Event::where('slug', $slug1)->first();
    if(!$event){
        // try normalized matching
        $event = Event::get()->first(function($e) use ($normName){
            $normEvent = strtolower(preg_replace('/[^a-z0-9]/','', $e->slug));
            return $normEvent === $normName;
        });
    }

    if($event){
        $event->image = 'events/'.basename($f);
        $event->save();
        echo "Updated {$event->id} -> {$event->image}\n";
    }else{
        echo "No match for {$f}\n";
    }
}
