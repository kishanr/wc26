<?php

namespace Database\Seeders;

use App\Models\Team;
use Illuminate\Database\Seeder;

class TeamSeeder extends Seeder
{
    /**
     * Official WC26 Draw - December 5, 2025
     * 48 teams in 12 groups (6 placeholder spots for playoff winners)
     */
    public function run(): void
    {
        $teams = [
            // Group A
            ['name' => ['en' => 'Mexico', 'es' => 'México', 'nl' => 'Mexico'], 'iso_code' => 'MEX', 'group' => 'A', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#006847', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'South Africa', 'nl' => 'Zuid-Afrika'], 'iso_code' => 'RSA', 'group' => 'A', 'confederation' => 'CAF', 'colors' => ['primary' => '#007749', 'secondary' => '#FFB81C']],
            ['name' => ['en' => 'South Korea', 'ko' => '대한민국', 'nl' => 'Zuid-Korea'], 'iso_code' => 'KOR', 'group' => 'A', 'confederation' => 'AFC', 'colors' => ['primary' => '#C60C30', 'secondary' => '#003478']],
            ['name' => ['en' => 'UEFA Playoff D Winner'], 'iso_code' => 'UD1', 'group' => 'A', 'is_placeholder' => true, 'placeholder_label' => 'UEFA Playoff D Winner'],

            // Group B
            ['name' => ['en' => 'Canada', 'fr' => 'Canada', 'nl' => 'Canada'], 'iso_code' => 'CAN', 'group' => 'B', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#FF0000', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'UEFA Playoff A Winner'], 'iso_code' => 'UA1', 'group' => 'B', 'is_placeholder' => true, 'placeholder_label' => 'Italy/N.Ireland/Wales/Bosnia'],
            ['name' => ['en' => 'Qatar', 'ar' => 'قطر', 'nl' => 'Qatar'], 'iso_code' => 'QAT', 'group' => 'B', 'confederation' => 'AFC', 'colors' => ['primary' => '#8D1B3D', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Switzerland', 'de' => 'Schweiz', 'fr' => 'Suisse', 'nl' => 'Zwitserland'], 'iso_code' => 'SUI', 'group' => 'B', 'confederation' => 'UEFA', 'colors' => ['primary' => '#FF0000', 'secondary' => '#FFFFFF']],

            // Group C
            ['name' => ['en' => 'Brazil', 'pt' => 'Brasil', 'nl' => 'Brazilië'], 'iso_code' => 'BRA', 'group' => 'C', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#009739', 'secondary' => '#FEDD00']],
            ['name' => ['en' => 'Morocco', 'ar' => 'المغرب', 'fr' => 'Maroc', 'nl' => 'Marokko'], 'iso_code' => 'MAR', 'group' => 'C', 'confederation' => 'CAF', 'colors' => ['primary' => '#C1272D', 'secondary' => '#006233']],
            ['name' => ['en' => 'Haiti', 'fr' => 'Haïti', 'nl' => 'Haïti'], 'iso_code' => 'HAI', 'group' => 'C', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#00209F', 'secondary' => '#D21034']],
            ['name' => ['en' => 'Scotland', 'gd' => 'Alba', 'nl' => 'Schotland'], 'iso_code' => 'SCO', 'group' => 'C', 'confederation' => 'UEFA', 'colors' => ['primary' => '#0065BF', 'secondary' => '#FFFFFF']],

            // Group D
            ['name' => ['en' => 'United States', 'es' => 'Estados Unidos', 'nl' => 'Verenigde Staten'], 'iso_code' => 'USA', 'group' => 'D', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#002868', 'secondary' => '#BF0A30']],
            ['name' => ['en' => 'Paraguay', 'es' => 'Paraguay', 'nl' => 'Paraguay'], 'iso_code' => 'PAR', 'group' => 'D', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#D52B1E', 'secondary' => '#0038A8']],
            ['name' => ['en' => 'Australia', 'nl' => 'Australië'], 'iso_code' => 'AUS', 'group' => 'D', 'confederation' => 'AFC', 'colors' => ['primary' => '#00843D', 'secondary' => '#FFCD00']],
            ['name' => ['en' => 'UEFA Playoff C Winner'], 'iso_code' => 'UC1', 'group' => 'D', 'is_placeholder' => true, 'placeholder_label' => 'Türkiye/Romania/Slovakia/Kosovo'],

            // Group E
            ['name' => ['en' => 'Germany', 'de' => 'Deutschland', 'nl' => 'Duitsland'], 'iso_code' => 'GER', 'group' => 'E', 'confederation' => 'UEFA', 'colors' => ['primary' => '#000000', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Curaçao', 'nl' => 'Curaçao'], 'iso_code' => 'CUW', 'group' => 'E', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#002B7F', 'secondary' => '#F9E814']],
            ['name' => ['en' => 'Ivory Coast', 'fr' => "Côte d'Ivoire", 'nl' => 'Ivoorkust'], 'iso_code' => 'CIV', 'group' => 'E', 'confederation' => 'CAF', 'colors' => ['primary' => '#FF8200', 'secondary' => '#009A44']],
            ['name' => ['en' => 'Ecuador', 'es' => 'Ecuador', 'nl' => 'Ecuador'], 'iso_code' => 'ECU', 'group' => 'E', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#FFD100', 'secondary' => '#034EA2']],

            // Group F
            ['name' => ['en' => 'Netherlands', 'nl' => 'Nederland'], 'iso_code' => 'NED', 'group' => 'F', 'confederation' => 'UEFA', 'colors' => ['primary' => '#FF6600', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Japan', 'ja' => '日本', 'nl' => 'Japan'], 'iso_code' => 'JPN', 'group' => 'F', 'confederation' => 'AFC', 'colors' => ['primary' => '#0033A0', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'UEFA Playoff B Winner'], 'iso_code' => 'UB1', 'group' => 'F', 'is_placeholder' => true, 'placeholder_label' => 'Ukraine/Sweden/Poland/Albania'],
            ['name' => ['en' => 'Tunisia', 'ar' => 'تونس', 'fr' => 'Tunisie', 'nl' => 'Tunesië'], 'iso_code' => 'TUN', 'group' => 'F', 'confederation' => 'CAF', 'colors' => ['primary' => '#E70013', 'secondary' => '#FFFFFF']],

            // Group G
            ['name' => ['en' => 'Belgium', 'nl' => 'België', 'fr' => 'Belgique'], 'iso_code' => 'BEL', 'group' => 'G', 'confederation' => 'UEFA', 'colors' => ['primary' => '#ED2939', 'secondary' => '#000000']],
            ['name' => ['en' => 'Egypt', 'ar' => 'مصر', 'nl' => 'Egypte'], 'iso_code' => 'EGY', 'group' => 'G', 'confederation' => 'CAF', 'colors' => ['primary' => '#CE1126', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Iran', 'fa' => 'ایران', 'nl' => 'Iran'], 'iso_code' => 'IRN', 'group' => 'G', 'confederation' => 'AFC', 'colors' => ['primary' => '#239F40', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'New Zealand', 'mi' => 'Aotearoa', 'nl' => 'Nieuw-Zeeland'], 'iso_code' => 'NZL', 'group' => 'G', 'confederation' => 'OFC', 'colors' => ['primary' => '#000000', 'secondary' => '#FFFFFF']],

            // Group H
            ['name' => ['en' => 'Spain', 'es' => 'España', 'nl' => 'Spanje'], 'iso_code' => 'ESP', 'group' => 'H', 'confederation' => 'UEFA', 'colors' => ['primary' => '#AA151B', 'secondary' => '#F1BF00']],
            ['name' => ['en' => 'Cape Verde', 'pt' => 'Cabo Verde', 'nl' => 'Kaapverdië'], 'iso_code' => 'CPV', 'group' => 'H', 'confederation' => 'CAF', 'colors' => ['primary' => '#003893', 'secondary' => '#CF2027']],
            ['name' => ['en' => 'Saudi Arabia', 'ar' => 'السعودية', 'nl' => 'Saudi-Arabië'], 'iso_code' => 'KSA', 'group' => 'H', 'confederation' => 'AFC', 'colors' => ['primary' => '#006C35', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Uruguay', 'es' => 'Uruguay', 'nl' => 'Uruguay'], 'iso_code' => 'URU', 'group' => 'H', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#0038A8', 'secondary' => '#FFFFFF']],

            // Group I
            ['name' => ['en' => 'France', 'fr' => 'France', 'nl' => 'Frankrijk'], 'iso_code' => 'FRA', 'group' => 'I', 'confederation' => 'UEFA', 'colors' => ['primary' => '#002395', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Senegal', 'fr' => 'Sénégal', 'nl' => 'Senegal'], 'iso_code' => 'SEN', 'group' => 'I', 'confederation' => 'CAF', 'colors' => ['primary' => '#00853F', 'secondary' => '#FDEF42']],
            ['name' => ['en' => 'Intercontinental Playoff 2'], 'iso_code' => 'IP2', 'group' => 'I', 'is_placeholder' => true, 'placeholder_label' => 'Bolivia/Suriname/Iraq'],
            ['name' => ['en' => 'Norway', 'no' => 'Norge', 'nl' => 'Noorwegen'], 'iso_code' => 'NOR', 'group' => 'I', 'confederation' => 'UEFA', 'colors' => ['primary' => '#EF2B2D', 'secondary' => '#002868']],

            // Group J
            ['name' => ['en' => 'Argentina', 'es' => 'Argentina', 'nl' => 'Argentinië'], 'iso_code' => 'ARG', 'group' => 'J', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#74ACDF', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Algeria', 'ar' => 'الجزائر', 'fr' => 'Algérie', 'nl' => 'Algerije'], 'iso_code' => 'ALG', 'group' => 'J', 'confederation' => 'CAF', 'colors' => ['primary' => '#006233', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Austria', 'de' => 'Österreich', 'nl' => 'Oostenrijk'], 'iso_code' => 'AUT', 'group' => 'J', 'confederation' => 'UEFA', 'colors' => ['primary' => '#ED2939', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Jordan', 'ar' => 'الأردن', 'nl' => 'Jordanië'], 'iso_code' => 'JOR', 'group' => 'J', 'confederation' => 'AFC', 'colors' => ['primary' => '#007A3D', 'secondary' => '#CE1126']],

            // Group K
            ['name' => ['en' => 'Portugal', 'pt' => 'Portugal', 'nl' => 'Portugal'], 'iso_code' => 'POR', 'group' => 'K', 'confederation' => 'UEFA', 'colors' => ['primary' => '#FF0000', 'secondary' => '#006600']],
            ['name' => ['en' => 'Intercontinental Playoff 1'], 'iso_code' => 'IP1', 'group' => 'K', 'is_placeholder' => true, 'placeholder_label' => 'Jamaica/New Caledonia/DR Congo'],
            ['name' => ['en' => 'Uzbekistan', 'uz' => "O'zbekiston", 'nl' => 'Oezbekistan'], 'iso_code' => 'UZB', 'group' => 'K', 'confederation' => 'AFC', 'colors' => ['primary' => '#0099B5', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Colombia', 'es' => 'Colombia', 'nl' => 'Colombia'], 'iso_code' => 'COL', 'group' => 'K', 'confederation' => 'CONMEBOL', 'colors' => ['primary' => '#FCD116', 'secondary' => '#003893']],

            // Group L
            ['name' => ['en' => 'England', 'nl' => 'Engeland'], 'iso_code' => 'ENG', 'group' => 'L', 'confederation' => 'UEFA', 'colors' => ['primary' => '#FFFFFF', 'secondary' => '#CF081F']],
            ['name' => ['en' => 'Croatia', 'hr' => 'Hrvatska', 'nl' => 'Kroatië'], 'iso_code' => 'CRO', 'group' => 'L', 'confederation' => 'UEFA', 'colors' => ['primary' => '#FF0000', 'secondary' => '#FFFFFF']],
            ['name' => ['en' => 'Ghana', 'nl' => 'Ghana'], 'iso_code' => 'GHA', 'group' => 'L', 'confederation' => 'CAF', 'colors' => ['primary' => '#006B3F', 'secondary' => '#FCD116']],
            ['name' => ['en' => 'Panama', 'es' => 'Panamá', 'nl' => 'Panama'], 'iso_code' => 'PAN', 'group' => 'L', 'confederation' => 'CONCACAF', 'colors' => ['primary' => '#DA121A', 'secondary' => '#072357']],

            // ============ KNOCKOUT STAGE PLACEHOLDERS ============
            // Round of 32 qualifiers (24 from groups + 8 best third-place)
            // Winners and Runners-up from each group
            ['name' => ['en' => 'Winner Group A'], 'iso_code' => 'W-A', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group A'],
            ['name' => ['en' => 'Winner Group B'], 'iso_code' => 'W-B', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group B'],
            ['name' => ['en' => 'Winner Group C'], 'iso_code' => 'W-C', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group C'],
            ['name' => ['en' => 'Winner Group D'], 'iso_code' => 'W-D', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group D'],
            ['name' => ['en' => 'Winner Group E'], 'iso_code' => 'W-E', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group E'],
            ['name' => ['en' => 'Winner Group F'], 'iso_code' => 'W-F', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group F'],
            ['name' => ['en' => 'Winner Group G'], 'iso_code' => 'W-G', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group G'],
            ['name' => ['en' => 'Winner Group H'], 'iso_code' => 'W-H', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group H'],
            ['name' => ['en' => 'Winner Group I'], 'iso_code' => 'W-I', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group I'],
            ['name' => ['en' => 'Winner Group J'], 'iso_code' => 'W-J', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group J'],
            ['name' => ['en' => 'Winner Group K'], 'iso_code' => 'W-K', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group K'],
            ['name' => ['en' => 'Winner Group L'], 'iso_code' => 'W-L', 'is_placeholder' => true, 'placeholder_label' => '1st Place Group L'],

            ['name' => ['en' => 'Runner-up Group A'], 'iso_code' => 'R-A', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group A'],
            ['name' => ['en' => 'Runner-up Group B'], 'iso_code' => 'R-B', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group B'],
            ['name' => ['en' => 'Runner-up Group C'], 'iso_code' => 'R-C', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group C'],
            ['name' => ['en' => 'Runner-up Group D'], 'iso_code' => 'R-D', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group D'],
            ['name' => ['en' => 'Runner-up Group E'], 'iso_code' => 'R-E', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group E'],
            ['name' => ['en' => 'Runner-up Group F'], 'iso_code' => 'R-F', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group F'],
            ['name' => ['en' => 'Runner-up Group G'], 'iso_code' => 'R-G', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group G'],
            ['name' => ['en' => 'Runner-up Group H'], 'iso_code' => 'R-H', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group H'],
            ['name' => ['en' => 'Runner-up Group I'], 'iso_code' => 'R-I', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group I'],
            ['name' => ['en' => 'Runner-up Group J'], 'iso_code' => 'R-J', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group J'],
            ['name' => ['en' => 'Runner-up Group K'], 'iso_code' => 'R-K', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group K'],
            ['name' => ['en' => 'Runner-up Group L'], 'iso_code' => 'R-L', 'is_placeholder' => true, 'placeholder_label' => '2nd Place Group L'],

            // Best third-place teams (8 of 12 qualify)
            ['name' => ['en' => 'Best 3rd #1'], 'iso_code' => '3-1', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 1'],
            ['name' => ['en' => 'Best 3rd #2'], 'iso_code' => '3-2', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 2'],
            ['name' => ['en' => 'Best 3rd #3'], 'iso_code' => '3-3', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 3'],
            ['name' => ['en' => 'Best 3rd #4'], 'iso_code' => '3-4', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 4'],
            ['name' => ['en' => 'Best 3rd #5'], 'iso_code' => '3-5', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 5'],
            ['name' => ['en' => 'Best 3rd #6'], 'iso_code' => '3-6', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 6'],
            ['name' => ['en' => 'Best 3rd #7'], 'iso_code' => '3-7', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 7'],
            ['name' => ['en' => 'Best 3rd #8'], 'iso_code' => '3-8', 'is_placeholder' => true, 'placeholder_label' => 'Best 3rd Place Team 8'],

            // Round of 16 winners
            ['name' => ['en' => 'R16 Match 1 Winner'], 'iso_code' => 'R16-1', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 1'],
            ['name' => ['en' => 'R16 Match 2 Winner'], 'iso_code' => 'R16-2', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 2'],
            ['name' => ['en' => 'R16 Match 3 Winner'], 'iso_code' => 'R16-3', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 3'],
            ['name' => ['en' => 'R16 Match 4 Winner'], 'iso_code' => 'R16-4', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 4'],
            ['name' => ['en' => 'R16 Match 5 Winner'], 'iso_code' => 'R16-5', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 5'],
            ['name' => ['en' => 'R16 Match 6 Winner'], 'iso_code' => 'R16-6', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 6'],
            ['name' => ['en' => 'R16 Match 7 Winner'], 'iso_code' => 'R16-7', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 7'],
            ['name' => ['en' => 'R16 Match 8 Winner'], 'iso_code' => 'R16-8', 'is_placeholder' => true, 'placeholder_label' => 'Winner R16 Match 8'],

            // Quarter-final winners
            ['name' => ['en' => 'QF Match 1 Winner'], 'iso_code' => 'QF-1', 'is_placeholder' => true, 'placeholder_label' => 'Winner QF Match 1'],
            ['name' => ['en' => 'QF Match 2 Winner'], 'iso_code' => 'QF-2', 'is_placeholder' => true, 'placeholder_label' => 'Winner QF Match 2'],
            ['name' => ['en' => 'QF Match 3 Winner'], 'iso_code' => 'QF-3', 'is_placeholder' => true, 'placeholder_label' => 'Winner QF Match 3'],
            ['name' => ['en' => 'QF Match 4 Winner'], 'iso_code' => 'QF-4', 'is_placeholder' => true, 'placeholder_label' => 'Winner QF Match 4'],

            // Quarter-final losers (for third-place match)
            ['name' => ['en' => 'QF Loser 1'], 'iso_code' => 'QFL-1', 'is_placeholder' => true, 'placeholder_label' => 'Loser QF Match linked to SF1'],
            ['name' => ['en' => 'QF Loser 2'], 'iso_code' => 'QFL-2', 'is_placeholder' => true, 'placeholder_label' => 'Loser QF Match linked to SF2'],

            // Semi-final winners
            ['name' => ['en' => 'SF Match 1 Winner'], 'iso_code' => 'SF-1', 'is_placeholder' => true, 'placeholder_label' => 'Winner SF Match 1'],
            ['name' => ['en' => 'SF Match 2 Winner'], 'iso_code' => 'SF-2', 'is_placeholder' => true, 'placeholder_label' => 'Winner SF Match 2'],

            // Semi-final losers (for third-place match)
            ['name' => ['en' => 'SF Loser 1'], 'iso_code' => 'SFL-1', 'is_placeholder' => true, 'placeholder_label' => 'Loser SF Match 1'],
            ['name' => ['en' => 'SF Loser 2'], 'iso_code' => 'SFL-2', 'is_placeholder' => true, 'placeholder_label' => 'Loser SF Match 2'],
        ];

        foreach ($teams as $team) {
            Team::create($team);
        }

        $this->command->info('✅ Created ' . count($teams) . ' teams for WC26 (including knockout placeholders)');
    }
}
