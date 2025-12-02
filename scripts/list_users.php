<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;

$users = User::all();
if($users->isEmpty()){
    echo "No users found\n";
    exit;
}

foreach($users as $u){
    echo "ID: {$u->id} | Name: {$u->name} | Email: {$u->email} | Role: {$u->role} | Created: {$u->created_at}\n";
}

$admins = $users->where('role','admin');
echo "\nAdmin count: " . $admins->count() . "\n";
if($admins->count()>0){
    foreach($admins as $a){
        echo "Admin ID: {$a->id} (email: {$a->email})\n";
    }
}
