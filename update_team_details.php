<?php

// Script to add team details (FIFA ranking, coach, captain, WC history) to existing teams

require __DIR__ . '/vendor/autoload.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$app->make(\Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Team;

$teamDetails = [
    // Group A
    'MEX' => ['fifa_ranking' => 15, 'coach' => 'Javier Aguirre', 'captain' => 'Guillermo Ochoa', 'world_cup_titles' => 0, 'world_cup_appearances' => 17],
    'RSA' => ['fifa_ranking' => 58, 'coach' => 'Hugo Broos', 'captain' => 'Ronwen Williams', 'world_cup_titles' => 0, 'world_cup_appearances' => 4],
    'KOR' => ['fifa_ranking' => 23, 'coach' => 'Jürgen Klinsmann', 'captain' => 'Son Heung-min', 'world_cup_titles' => 0, 'world_cup_appearances' => 11],
    
    // Group B
    'CAN' => ['fifa_ranking' => 48, 'coach' => 'Jesse Marsch', 'captain' => 'Alphonso Davies', 'world_cup_titles' => 0, 'world_cup_appearances' => 2],
    'QAT' => ['fifa_ranking' => 61, 'coach' => 'Tintín Márquez', 'captain' => 'Hassan Al-Haydos', 'world_cup_titles' => 0, 'world_cup_appearances' => 2],
    'SUI' => ['fifa_ranking' => 19, 'coach' => 'Murat Yakin', 'captain' => 'Granit Xhaka', 'world_cup_titles' => 0, 'world_cup_appearances' => 12],
    
    // Group C
    'BRA' => ['fifa_ranking' => 5, 'coach' => 'Dorival Júnior', 'captain' => 'Danilo', 'world_cup_titles' => 5, 'world_cup_appearances' => 22],
    'MAR' => ['fifa_ranking' => 13, 'coach' => 'Walid Regragui', 'captain' => 'Romain Saïss', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    'HAI' => ['fifa_ranking' => 85, 'coach' => 'Gabriel Calderón Pellegrino', 'captain' => 'Duckens Nazon', 'world_cup_titles' => 0, 'world_cup_appearances' => 1],
    'SCO' => ['fifa_ranking' => 39, 'coach' => 'Steve Clarke', 'captain' => 'Andrew Robertson', 'world_cup_titles' => 0, 'world_cup_appearances' => 8],
    
    // Group D
    'USA' => ['fifa_ranking' => 11, 'coach' => 'Gregg Berhalter', 'captain' => 'Christian Pulisic', 'world_cup_titles' => 0, 'world_cup_appearances' => 11],
    'PAR' => ['fifa_ranking' => 62, 'coach' => 'Daniel Garnero', 'captain' => 'Gustavo Gómez', 'world_cup_titles' => 0, 'world_cup_appearances' => 8],
    'AUS' => ['fifa_ranking' => 25, 'coach' => 'Graham Arnold', 'captain' => 'Mathew Ryan', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    
    // Group E
    'GER' => ['fifa_ranking' => 16, 'coach' => 'Julian Nagelsmann', 'captain' => 'İlkay Gündoğan', 'world_cup_titles' => 4, 'world_cup_appearances' => 20],
    'CUW' => ['fifa_ranking' => 82, 'coach' => 'Dick Advocaat', 'captain' => 'Leandro Bacuna', 'world_cup_titles' => 0, 'world_cup_appearances' => 0],
    'CIV' => ['fifa_ranking' => 39, 'coach' => 'Jean-Louis Gasset', 'captain' => 'Serge Aurier', 'world_cup_titles' => 0, 'world_cup_appearances' => 3],
    'ECU' => ['fifa_ranking' => 30, 'coach' => 'Félix Sánchez', 'captain' => 'Enner Valencia', 'world_cup_titles' => 0, 'world_cup_appearances' => 4],
    
    // Group F
    'NED' => ['fifa_ranking' => 7, 'coach' => 'Ronald Koeman', 'captain' => 'Virgil van Dijk', 'world_cup_titles' => 0, 'world_cup_appearances' => 11],
    'JPN' => ['fifa_ranking' => 18, 'coach' => 'Hajime Moriyasu', 'captain' => 'Maya Yoshida', 'world_cup_titles' => 0, 'world_cup_appearances' => 7],
    'TUN' => ['fifa_ranking' => 28, 'coach' => 'Jalel Kadri', 'captain' => 'Youssef Msakni', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    
    // Group G
    'BEL' => ['fifa_ranking' => 8, 'coach' => 'Domenico Tedesco', 'captain' => 'Kevin De Bruyne', 'world_cup_titles' => 0, 'world_cup_appearances' => 14],
    'EGY' => ['fifa_ranking' => 36, 'coach' => 'Rui Vitória', 'captain' => 'Mohamed Salah', 'world_cup_titles' => 0, 'world_cup_appearances' => 3],
    'IRN' => ['fifa_ranking' => 21, 'coach' => 'Amir Ghalenoei', 'captain' => 'Ehsan Hajsafi', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    'NZL' => ['fifa_ranking' => 95, 'coach' => 'Danny Hay', 'captain' => 'Winston Reid', 'world_cup_titles' => 0, 'world_cup_appearances' => 2],
    
    // Group H
    'ESP' => ['fifa_ranking' => 10, 'coach' => 'Luis de la Fuente', 'captain' => 'Álvaro Morata', 'world_cup_titles' => 1, 'world_cup_appearances' => 16],
    'CPV' => ['fifa_ranking' => 73, 'coach' => 'Bubista', 'captain' => 'Nuno Borges', 'world_cup_titles' => 0, 'world_cup_appearances' => 0],
    'KSA' => ['fifa_ranking' => 56, 'coach' => 'Roberto Mancini', 'captain' => 'Salman Al-Faraj', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    'URU' => ['fifa_ranking' => 14, 'coach' => 'Marcelo Bielsa', 'captain' => 'José María Giménez', 'world_cup_titles' => 2, 'world_cup_appearances' => 14],
    
    // Group I
    'FRA' => ['fifa_ranking' => 2, 'coach' => 'Didier Deschamps', 'captain' => 'Kylian Mbappé', 'world_cup_titles' => 2, 'world_cup_appearances' => 16],
    'SEN' => ['fifa_ranking' => 20, 'coach' => 'Aliou Cissé', 'captain' => 'Kalidou Koulibaly', 'world_cup_titles' => 0, 'world_cup_appearances' => 3],
    'NOR' => ['fifa_ranking' => 47, 'coach' => 'Ståle Solbakken', 'captain' => 'Martin Ødegaard', 'world_cup_titles' => 0, 'world_cup_appearances' => 3],
    
    // Group J
    'ARG' => ['fifa_ranking' => 1, 'coach' => 'Lionel Scaloni', 'captain' => 'Lionel Messi', 'world_cup_titles' => 3, 'world_cup_appearances' => 18],
    'ALG' => ['fifa_ranking' => 37, 'coach' => 'Vladimir Petkovic', 'captain' => 'Riyad Mahrez', 'world_cup_titles' => 0, 'world_cup_appearances' => 4],
    'AUT' => ['fifa_ranking' => 27, 'coach' => 'Ralf Rangnick', 'captain' => 'David Alaba', 'world_cup_titles' => 0, 'world_cup_appearances' => 7],
    'JOR' => ['fifa_ranking' => 87, 'coach' => 'Hussein Ammouta', 'captain' => 'Yazan Al-Naimat', 'world_cup_titles' => 0, 'world_cup_appearances' => 0],
    
    // Group K
    'POR' => ['fifa_ranking' => 6, 'coach' => 'Roberto Martínez', 'captain' => 'Cristiano Ronaldo', 'world_cup_titles' => 0, 'world_cup_appearances' => 8],
    'UZB' => ['fifa_ranking' => 68, 'coach' => 'Srečko Katanec', 'captain' => 'Odil Ahmedov', 'world_cup_titles' => 0, 'world_cup_appearances' => 0],
    'COL' => ['fifa_ranking' => 12, 'coach' => 'Néstor Lorenzo', 'captain' => 'James Rodríguez', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    
    // Group L
    'ENG' => ['fifa_ranking' => 4, 'coach' => 'Gareth Southgate', 'captain' => 'Harry Kane', 'world_cup_titles' => 1, 'world_cup_appearances' => 16],
    'CRO' => ['fifa_ranking' => 9, 'coach' => 'Zlatko Dalić', 'captain' => 'Luka Modrić', 'world_cup_titles' => 0, 'world_cup_appearances' => 6],
    'GHA' => ['fifa_ranking' => 60, 'coach' => 'Chris Hughton', 'captain' => 'André Ayew', 'world_cup_titles' => 0, 'world_cup_appearances' => 4],
    'PAN' => ['fifa_ranking' => 56, 'coach' => 'Thomas Christiansen', 'captain' => 'Aníbal Godoy', 'world_cup_titles' => 0, 'world_cup_appearances' => 1],
];

$updated = 0;
foreach ($teamDetails as $isoCode => $details) {
    $team = Team::where('iso_code', $isoCode)->first();
    if ($team) {
        $team->update($details);
        $updated++;
        echo "✅ Updated {$team->display_name}\n";
    }
}

echo "\n🎉 Successfully updated {$updated} teams with FIFA rankings, coaches, captains, and World Cup history!\n";
