<?php

use App\Models\Team;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$playoffTeams = [
    // Intercontinental
    ['name' => 'Bolivia', 'iso' => 'BOL'],
    ['name' => 'DR Congo', 'iso' => 'COD'],
    ['name' => 'Iraq', 'iso' => 'IRQ'],
    ['name' => 'Jamaica', 'iso' => 'JAM'],
    ['name' => 'New Caledonia', 'iso' => 'NCL'],
    ['name' => 'Suriname', 'iso' => 'SUR'],
    
    // UEFA
    ['name' => 'Slovakia', 'iso' => 'SVK'],
    ['name' => 'Ukraine', 'iso' => 'UKR'],
    ['name' => 'Republic of Ireland', 'iso' => 'IRL'],
    ['name' => 'Poland', 'iso' => 'POL'],
    ['name' => 'Italy', 'iso' => 'ITA'],
    ['name' => 'Albania', 'iso' => 'ALB'],
    ['name' => 'Czechia', 'iso' => 'CZE'],
    ['name' => 'Bosnia and Herzegovina', 'iso' => 'BIH'],
    ['name' => 'Wales', 'iso' => 'WAL'],
    ['name' => 'Kosovo', 'iso' => 'KVX'], // or KOS
    ['name' => 'Turkey', 'iso' => 'TUR'],
    ['name' => 'Denmark', 'iso' => 'DEN'],
    ['name' => 'Romania', 'iso' => 'ROU'],
    ['name' => 'Sweden', 'iso' => 'SWE'],
    ['name' => 'Northern Ireland', 'iso' => 'NIR'],
    ['name' => 'North Macedonia', 'iso' => 'MKD'],
];

foreach ($playoffTeams as $data) {
    $team = Team::where('iso_code', $data['iso'])->first();
    
    if (!$team) {
        $team = Team::create([
            'name' => ['en' => $data['name']],
            'iso_code' => $data['iso'],
            'is_placeholder' => false,
        ]);
        echo "Created {$data['name']}\n";
    } else {
        $team->is_placeholder = false;
        $team->save();
        echo "Updated {$data['name']} (Existing)\n";
    }
}
