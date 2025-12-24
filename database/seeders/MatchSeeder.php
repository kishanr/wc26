<?php

namespace Database\Seeders;

use App\Models\Game;
use App\Models\Stadium;
use App\Models\Team;
use Illuminate\Database\Seeder;
use Carbon\Carbon;
use Illuminate\Support\Facades\Schema;

class MatchSeeder extends Seeder
{
    /**
     * Official FIFA World Cup 2026 Match Schedule
     * Source: FIFA Official Schedule (December 2025/2026)
     */
    public function run(): void
    {
        $this->command->info('🏟️ Seeding WC26 matches...');

        // Clear existing games to prevent duplicates
        Schema::disableForeignKeyConstraints();
        Game::truncate();
        Schema::enableForeignKeyConstraints();

        $this->ensureKnockoutPlaceholders();

        // Cache teams and stadiums for faster lookup
        $teams = Team::all()->keyBy('iso_code');
        $stadiums = Stadium::all()->keyBy('city');

        // Define all matches - Group Stage + Knockout
        $matches = array_merge(
            $this->getGroupStageMatches(),
            $this->getKnockoutMatches()
        );

        $count = 0;
        $skipped = 0;
        foreach ($matches as $match) {
            $homeTeam = $teams[$match['home']] ?? null;
            $awayTeam = $teams[$match['away']] ?? null;
            $stadium = $this->findStadium($stadiums, $match['venue']);

            if (!$homeTeam || !$awayTeam) {
                // Try refreshing cache once if missing (in case we added more dynamically)
                 if (!$homeTeam) $homeTeam = Team::where('iso_code', $match['home'])->first();
                 if (!$awayTeam) $awayTeam = Team::where('iso_code', $match['away'])->first();
            }

            if (!$homeTeam || !$awayTeam) {
                $this->command->warn("⚠️ Skipping: {$match['home']} vs {$match['away']} - team not found");
                $skipped++;
                continue;
            }

            Game::create([
                'home_team_id' => $homeTeam->id,
                'away_team_id' => $awayTeam->id,
                'stadium_id' => $stadium?->id,
                'start_time' => Carbon::parse($match['datetime'], $match['timezone'] ?? 'America/New_York'),
                'status' => 'scheduled',
                'stage' => $match['stage'],
                'group' => $match['group'] ?? null,
                'matchday' => $match['matchday'] ?? null,
            ]);
            $count++;
        }

        $this->command->info("✅ Created {$count} matches for WC26" . ($skipped > 0 ? " ({$skipped} skipped)" : ""));
    }

    /**
     * Ensure missing knockout placeholders exist (specifically R32 winners)
     */
    private function ensureKnockoutPlaceholders(): void
    {
        // Winners of Match 73 to 88 (for R16 usage)
        for ($i = 73; $i <= 88; $i++) {
            $code = "W$i";
            if (!Team::where('iso_code', $code)->exists()) {
                Team::create([
                    'name' => ['en' => "Winner Match $i"],
                    'iso_code' => $code,
                    'is_placeholder' => true,
                    'placeholder_label' => "Winner Match $i",
                ]);
            }
        }
        $this->command->info('✅ Ensured R32 winner placeholders exist.');
    }

    /**
     * Find stadium by city name (flexible matching)
     */
    private function findStadium($stadiums, string $venue): ?Stadium
    {
        // Direct city match
        if (isset($stadiums[$venue])) {
            return $stadiums[$venue];
        }

        // Flexible matching for venue names
        $venueMap = [
            'Mexico City' => 'Mexico City',
            'Guadalajara' => 'Guadalajara',
            'Monterrey' => 'Monterrey',
            'Atlanta' => 'Atlanta',
            'Boston' => 'Foxborough',
            'Dallas' => 'Arlington',
            'Houston' => 'Houston',
            'Kansas City' => 'Kansas City',
            'Los Angeles' => 'Inglewood',
            'Miami' => 'Miami Gardens',
            'New York' => 'East Rutherford',
            'New Jersey' => 'East Rutherford',
            'MetLife' => 'East Rutherford',
            'Philadelphia' => 'Philadelphia',
            'San Francisco' => 'Santa Clara',
            'Bay Area' => 'Santa Clara',
            'Seattle' => 'Seattle',
            'Toronto' => 'Toronto',
            'Vancouver' => 'Vancouver',
        ];

        foreach ($venueMap as $key => $city) {
            if (stripos($venue, $key) !== false) {
                return $stadiums[$city] ?? null;
            }
        }

        return null;
    }

    /**
     * Group Stage Matches
     */
    private function getGroupStageMatches(): array
    {
        return [
            // Thursday, 11 June 2026
            ['home' => 'MEX', 'away' => 'RSA', 'datetime' => '2026-06-11 13:00', 'venue' => 'Mexico City', 'stage' => 'group', 'group' => 'A', 'matchday' => 1, 'timezone' => 'America/Mexico_City'],
            ['home' => 'KOR', 'away' => 'UD1', 'datetime' => '2026-06-11 19:00', 'venue' => 'Guadalajara', 'stage' => 'group', 'group' => 'A', 'matchday' => 1, 'timezone' => 'America/Mexico_City'],

            // Friday, 12 June 2026
            ['home' => 'CAN', 'away' => 'UA1', 'datetime' => '2026-06-12 15:00', 'venue' => 'Toronto', 'stage' => 'group', 'group' => 'B', 'matchday' => 1, 'timezone' => 'America/Toronto'],
            ['home' => 'USA', 'away' => 'PAR', 'datetime' => '2026-06-12 18:00', 'venue' => 'Los Angeles', 'stage' => 'group', 'group' => 'D', 'matchday' => 1, 'timezone' => 'America/Los_Angeles'],

            // Saturday, 13 June 2026
            ['home' => 'HAI', 'away' => 'SCO', 'datetime' => '2026-06-13 15:00', 'venue' => 'Boston', 'stage' => 'group', 'group' => 'C', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'AUS', 'away' => 'UC1', 'datetime' => '2026-06-13 12:00', 'venue' => 'Vancouver', 'stage' => 'group', 'group' => 'D', 'matchday' => 1, 'timezone' => 'America/Vancouver'],
            ['home' => 'BRA', 'away' => 'MAR', 'datetime' => '2026-06-13 18:00', 'venue' => 'New York', 'stage' => 'group', 'group' => 'C', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'QAT', 'away' => 'SUI', 'datetime' => '2026-06-13 13:00', 'venue' => 'San Francisco', 'stage' => 'group', 'group' => 'B', 'matchday' => 1, 'timezone' => 'America/Los_Angeles'],

            // Sunday, 14 June 2026
            ['home' => 'CIV', 'away' => 'ECU', 'datetime' => '2026-06-14 13:00', 'venue' => 'Philadelphia', 'stage' => 'group', 'group' => 'E', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'GER', 'away' => 'CUW', 'datetime' => '2026-06-14 12:00', 'venue' => 'Houston', 'stage' => 'group', 'group' => 'E', 'matchday' => 1, 'timezone' => 'America/Chicago'],
            ['home' => 'NED', 'away' => 'JPN', 'datetime' => '2026-06-14 15:00', 'venue' => 'Dallas', 'stage' => 'group', 'group' => 'F', 'matchday' => 1, 'timezone' => 'America/Chicago'],
            ['home' => 'UB1', 'away' => 'TUN', 'datetime' => '2026-06-14 18:00', 'venue' => 'Monterrey', 'stage' => 'group', 'group' => 'F', 'matchday' => 1, 'timezone' => 'America/Monterrey'],

            // Monday, 15 June 2026
            ['home' => 'KSA', 'away' => 'URU', 'datetime' => '2026-06-15 13:00', 'venue' => 'Miami', 'stage' => 'group', 'group' => 'H', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'ESP', 'away' => 'CPV', 'datetime' => '2026-06-15 16:00', 'venue' => 'Atlanta', 'stage' => 'group', 'group' => 'H', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'IRN', 'away' => 'NZL', 'datetime' => '2026-06-15 15:00', 'venue' => 'Los Angeles', 'stage' => 'group', 'group' => 'G', 'matchday' => 1, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'BEL', 'away' => 'EGY', 'datetime' => '2026-06-15 18:00', 'venue' => 'Seattle', 'stage' => 'group', 'group' => 'G', 'matchday' => 1, 'timezone' => 'America/Los_Angeles'],

            // Tuesday, 16 June 2026
            ['home' => 'FRA', 'away' => 'SEN', 'datetime' => '2026-06-16 13:00', 'venue' => 'New York', 'stage' => 'group', 'group' => 'I', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'IP2', 'away' => 'NOR', 'datetime' => '2026-06-16 18:00', 'venue' => 'Boston', 'stage' => 'group', 'group' => 'I', 'matchday' => 1, 'timezone' => 'America/New_York'],
            ['home' => 'ARG', 'away' => 'ALG', 'datetime' => '2026-06-16 12:00', 'venue' => 'Kansas City', 'stage' => 'group', 'group' => 'J', 'matchday' => 1, 'timezone' => 'America/Chicago'],
            ['home' => 'AUT', 'away' => 'JOR', 'datetime' => '2026-06-16 15:00', 'venue' => 'San Francisco', 'stage' => 'group', 'group' => 'J', 'matchday' => 1, 'timezone' => 'America/Los_Angeles'],

            // Wednesday, 17 June 2026
            ['home' => 'GHA', 'away' => 'PAN', 'datetime' => '2026-06-17 12:00', 'venue' => 'Toronto', 'stage' => 'group', 'group' => 'L', 'matchday' => 1, 'timezone' => 'America/Toronto'],
            ['home' => 'ENG', 'away' => 'CRO', 'datetime' => '2026-06-17 15:00', 'venue' => 'Dallas', 'stage' => 'group', 'group' => 'L', 'matchday' => 1, 'timezone' => 'America/Chicago'],
            ['home' => 'POR', 'away' => 'IP1', 'datetime' => '2026-06-17 18:00', 'venue' => 'Houston', 'stage' => 'group', 'group' => 'K', 'matchday' => 1, 'timezone' => 'America/Chicago'],
            ['home' => 'UZB', 'away' => 'COL', 'datetime' => '2026-06-17 13:00', 'venue' => 'Mexico City', 'stage' => 'group', 'group' => 'K', 'matchday' => 1, 'timezone' => 'America/Mexico_City'],

            // Thursday, 18 June 2026
            ['home' => 'UD1', 'away' => 'RSA', 'datetime' => '2026-06-18 13:00', 'venue' => 'Atlanta', 'stage' => 'group', 'group' => 'A', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'SUI', 'away' => 'UA1', 'datetime' => '2026-06-18 15:00', 'venue' => 'Los Angeles', 'stage' => 'group', 'group' => 'B', 'matchday' => 2, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'CAN', 'away' => 'QAT', 'datetime' => '2026-06-18 18:00', 'venue' => 'Vancouver', 'stage' => 'group', 'group' => 'B', 'matchday' => 2, 'timezone' => 'America/Vancouver'],
            ['home' => 'MEX', 'away' => 'KOR', 'datetime' => '2026-06-18 19:00', 'venue' => 'Guadalajara', 'stage' => 'group', 'group' => 'A', 'matchday' => 2, 'timezone' => 'America/Mexico_City'],

            // Friday, 19 June 2026
            ['home' => 'BRA', 'away' => 'HAI', 'datetime' => '2026-06-19 13:00', 'venue' => 'Philadelphia', 'stage' => 'group', 'group' => 'C', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'SCO', 'away' => 'MAR', 'datetime' => '2026-06-19 16:00', 'venue' => 'Boston', 'stage' => 'group', 'group' => 'C', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'UC1', 'away' => 'PAR', 'datetime' => '2026-06-19 15:00', 'venue' => 'San Francisco', 'stage' => 'group', 'group' => 'D', 'matchday' => 2, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'USA', 'away' => 'AUS', 'datetime' => '2026-06-19 18:00', 'venue' => 'Seattle', 'stage' => 'group', 'group' => 'D', 'matchday' => 2, 'timezone' => 'America/Los_Angeles'],

            // Saturday, 20 June 2026
            ['home' => 'GER', 'away' => 'CIV', 'datetime' => '2026-06-20 12:00', 'venue' => 'Toronto', 'stage' => 'group', 'group' => 'E', 'matchday' => 2, 'timezone' => 'America/Toronto'],
            ['home' => 'ECU', 'away' => 'CUW', 'datetime' => '2026-06-20 15:00', 'venue' => 'Kansas City', 'stage' => 'group', 'group' => 'E', 'matchday' => 2, 'timezone' => 'America/Chicago'],
            ['home' => 'NED', 'away' => 'UB1', 'datetime' => '2026-06-20 18:00', 'venue' => 'Houston', 'stage' => 'group', 'group' => 'F', 'matchday' => 2, 'timezone' => 'America/Chicago'],
            ['home' => 'TUN', 'away' => 'JPN', 'datetime' => '2026-06-20 19:00', 'venue' => 'Monterrey', 'stage' => 'group', 'group' => 'F', 'matchday' => 2, 'timezone' => 'America/Monterrey'],

            // Sunday, 21 June 2026
            ['home' => 'URU', 'away' => 'CPV', 'datetime' => '2026-06-21 13:00', 'venue' => 'Miami', 'stage' => 'group', 'group' => 'H', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'ESP', 'away' => 'KSA', 'datetime' => '2026-06-21 16:00', 'venue' => 'Atlanta', 'stage' => 'group', 'group' => 'H', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'BEL', 'away' => 'IRN', 'datetime' => '2026-06-21 15:00', 'venue' => 'Los Angeles', 'stage' => 'group', 'group' => 'G', 'matchday' => 2, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'NZL', 'away' => 'EGY', 'datetime' => '2026-06-21 12:00', 'venue' => 'Vancouver', 'stage' => 'group', 'group' => 'G', 'matchday' => 2, 'timezone' => 'America/Vancouver'],

            // Monday, 22 June 2026
            ['home' => 'NOR', 'away' => 'SEN', 'datetime' => '2026-06-22 13:00', 'venue' => 'New York', 'stage' => 'group', 'group' => 'I', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'FRA', 'away' => 'IP2', 'datetime' => '2026-06-22 16:00', 'venue' => 'Philadelphia', 'stage' => 'group', 'group' => 'I', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'ARG', 'away' => 'AUT', 'datetime' => '2026-06-22 15:00', 'venue' => 'Dallas', 'stage' => 'group', 'group' => 'J', 'matchday' => 2, 'timezone' => 'America/Chicago'],
            ['home' => 'JOR', 'away' => 'ALG', 'datetime' => '2026-06-22 18:00', 'venue' => 'San Francisco', 'stage' => 'group', 'group' => 'J', 'matchday' => 2, 'timezone' => 'America/Los_Angeles'],

            // Tuesday, 23 June 2026
            ['home' => 'ENG', 'away' => 'GHA', 'datetime' => '2026-06-23 13:00', 'venue' => 'Boston', 'stage' => 'group', 'group' => 'L', 'matchday' => 2, 'timezone' => 'America/New_York'],
            ['home' => 'PAN', 'away' => 'CRO', 'datetime' => '2026-06-23 16:00', 'venue' => 'Toronto', 'stage' => 'group', 'group' => 'L', 'matchday' => 2, 'timezone' => 'America/Toronto'],
            ['home' => 'POR', 'away' => 'UZB', 'datetime' => '2026-06-23 18:00', 'venue' => 'Houston', 'stage' => 'group', 'group' => 'K', 'matchday' => 2, 'timezone' => 'America/Chicago'],
            ['home' => 'COL', 'away' => 'IP1', 'datetime' => '2026-06-23 19:00', 'venue' => 'Guadalajara', 'stage' => 'group', 'group' => 'K', 'matchday' => 2, 'timezone' => 'America/Mexico_City'],

            // Wednesday, 24 June 2026
            ['home' => 'SCO', 'away' => 'BRA', 'datetime' => '2026-06-24 16:00', 'venue' => 'Miami', 'stage' => 'group', 'group' => 'C', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'MAR', 'away' => 'HAI', 'datetime' => '2026-06-24 16:00', 'venue' => 'Atlanta', 'stage' => 'group', 'group' => 'C', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'SUI', 'away' => 'CAN', 'datetime' => '2026-06-24 19:00', 'venue' => 'Vancouver', 'stage' => 'group', 'group' => 'B', 'matchday' => 3, 'timezone' => 'America/Vancouver'],
            ['home' => 'UA1', 'away' => 'QAT', 'datetime' => '2026-06-24 19:00', 'venue' => 'Seattle', 'stage' => 'group', 'group' => 'B', 'matchday' => 3, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'UD1', 'away' => 'MEX', 'datetime' => '2026-06-24 13:00', 'venue' => 'Mexico City', 'stage' => 'group', 'group' => 'A', 'matchday' => 3, 'timezone' => 'America/Mexico_City'],
            ['home' => 'RSA', 'away' => 'KOR', 'datetime' => '2026-06-24 13:00', 'venue' => 'Monterrey', 'stage' => 'group', 'group' => 'A', 'matchday' => 3, 'timezone' => 'America/Monterrey'],

            // Thursday, 25 June 2026
            ['home' => 'CUW', 'away' => 'CIV', 'datetime' => '2026-06-25 15:00', 'venue' => 'Philadelphia', 'stage' => 'group', 'group' => 'E', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'ECU', 'away' => 'GER', 'datetime' => '2026-06-25 15:00', 'venue' => 'New York', 'stage' => 'group', 'group' => 'E', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'JPN', 'away' => 'UB1', 'datetime' => '2026-06-25 12:00', 'venue' => 'Dallas', 'stage' => 'group', 'group' => 'F', 'matchday' => 3, 'timezone' => 'America/Chicago'],
            ['home' => 'TUN', 'away' => 'NED', 'datetime' => '2026-06-25 12:00', 'venue' => 'Kansas City', 'stage' => 'group', 'group' => 'F', 'matchday' => 3, 'timezone' => 'America/Chicago'],
            ['home' => 'UC1', 'away' => 'USA', 'datetime' => '2026-06-25 18:00', 'venue' => 'Los Angeles', 'stage' => 'group', 'group' => 'D', 'matchday' => 3, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'PAR', 'away' => 'AUS', 'datetime' => '2026-06-25 18:00', 'venue' => 'San Francisco', 'stage' => 'group', 'group' => 'D', 'matchday' => 3, 'timezone' => 'America/Los_Angeles'],

            // Friday, 26 June 2026
            ['home' => 'NOR', 'away' => 'FRA', 'datetime' => '2026-06-26 13:00', 'venue' => 'Boston', 'stage' => 'group', 'group' => 'I', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'SEN', 'away' => 'IP2', 'datetime' => '2026-06-26 13:00', 'venue' => 'Toronto', 'stage' => 'group', 'group' => 'I', 'matchday' => 3, 'timezone' => 'America/Toronto'],
            ['home' => 'EGY', 'away' => 'IRN', 'datetime' => '2026-06-26 16:00', 'venue' => 'Seattle', 'stage' => 'group', 'group' => 'G', 'matchday' => 3, 'timezone' => 'America/Los_Angeles'],
            ['home' => 'NZL', 'away' => 'BEL', 'datetime' => '2026-06-26 16:00', 'venue' => 'Vancouver', 'stage' => 'group', 'group' => 'G', 'matchday' => 3, 'timezone' => 'America/Vancouver'],
            ['home' => 'CPV', 'away' => 'KSA', 'datetime' => '2026-06-26 19:00', 'venue' => 'Houston', 'stage' => 'group', 'group' => 'H', 'matchday' => 3, 'timezone' => 'America/Chicago'],
            ['home' => 'URU', 'away' => 'ESP', 'datetime' => '2026-06-26 19:00', 'venue' => 'Guadalajara', 'stage' => 'group', 'group' => 'H', 'matchday' => 3, 'timezone' => 'America/Mexico_City'],

            // Saturday, 27 June 2026
            ['home' => 'PAN', 'away' => 'ENG', 'datetime' => '2026-06-27 15:00', 'venue' => 'New York', 'stage' => 'group', 'group' => 'L', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'CRO', 'away' => 'GHA', 'datetime' => '2026-06-27 15:00', 'venue' => 'Philadelphia', 'stage' => 'group', 'group' => 'L', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'ALG', 'away' => 'AUT', 'datetime' => '2026-06-27 12:00', 'venue' => 'Kansas City', 'stage' => 'group', 'group' => 'J', 'matchday' => 3, 'timezone' => 'America/Chicago'],
            ['home' => 'JOR', 'away' => 'ARG', 'datetime' => '2026-06-27 12:00', 'venue' => 'Dallas', 'stage' => 'group', 'group' => 'J', 'matchday' => 3, 'timezone' => 'America/Chicago'],
            ['home' => 'COL', 'away' => 'POR', 'datetime' => '2026-06-27 18:00', 'venue' => 'Miami', 'stage' => 'group', 'group' => 'K', 'matchday' => 3, 'timezone' => 'America/New_York'],
            ['home' => 'IP1', 'away' => 'UZB', 'datetime' => '2026-06-27 18:00', 'venue' => 'Atlanta', 'stage' => 'group', 'group' => 'K', 'matchday' => 3, 'timezone' => 'America/New_York'],
        ];
    }

    /**
     * Knockout Stage Matches
     */
    private function getKnockoutMatches(): array
    {
        return [
            // ============ ROUND OF 32 (Matches 73-88) ============
            // Winners will be W73...W88
            
            // Sunday, 28 June 2026
            ['home' => 'R-A', 'away' => 'R-B', 'datetime' => '2026-06-28 13:00', 'venue' => 'Los Angeles', 'stage' => 'round_of_32', 'timezone' => 'America/Los_Angeles'], // Match 73
            
            // Monday, 29 June 2026
            ['home' => 'W-E', 'away' => '3-1', 'datetime' => '2026-06-29 13:00', 'venue' => 'Boston', 'stage' => 'round_of_32', 'timezone' => 'America/New_York'], // Match 74
            ['home' => 'W-F', 'away' => 'R-C', 'datetime' => '2026-06-29 16:00', 'venue' => 'Monterrey', 'stage' => 'round_of_32', 'timezone' => 'America/Monterrey'], // Match 75
            ['home' => 'W-C', 'away' => 'R-F', 'datetime' => '2026-06-29 19:00', 'venue' => 'Houston', 'stage' => 'round_of_32', 'timezone' => 'America/Chicago'], // Match 76

            // Tuesday, 30 June 2026
            ['home' => 'W-I', 'away' => '3-2', 'datetime' => '2026-06-30 13:00', 'venue' => 'New York', 'stage' => 'round_of_32', 'timezone' => 'America/New_York'], // Match 77
            ['home' => 'R-E', 'away' => 'R-I', 'datetime' => '2026-06-30 16:00', 'venue' => 'Dallas', 'stage' => 'round_of_32', 'timezone' => 'America/Chicago'], // Match 78
            ['home' => 'W-A', 'away' => '3-3', 'datetime' => '2026-06-30 19:00', 'venue' => 'Mexico City', 'stage' => 'round_of_32', 'timezone' => 'America/Mexico_City'], // Match 79

            // Wednesday, 1 July 2026
            ['home' => 'W-L', 'away' => '3-4', 'datetime' => '2026-07-01 13:00', 'venue' => 'Atlanta', 'stage' => 'round_of_32', 'timezone' => 'America/New_York'], // Match 80
            ['home' => 'W-D', 'away' => '3-5', 'datetime' => '2026-07-01 16:00', 'venue' => 'San Francisco', 'stage' => 'round_of_32', 'timezone' => 'America/Los_Angeles'], // Match 81
            ['home' => 'W-G', 'away' => '3-6', 'datetime' => '2026-07-01 19:00', 'venue' => 'Seattle', 'stage' => 'round_of_32', 'timezone' => 'America/Los_Angeles'], // Match 82

            // Thursday, 2 July 2026
            ['home' => 'R-K', 'away' => 'R-L', 'datetime' => '2026-07-02 13:00', 'venue' => 'Toronto', 'stage' => 'round_of_32', 'timezone' => 'America/Toronto'], // Match 83
            ['home' => 'W-H', 'away' => 'R-J', 'datetime' => '2026-07-02 16:00', 'venue' => 'Los Angeles', 'stage' => 'round_of_32', 'timezone' => 'America/Los_Angeles'], // Match 84
            ['home' => 'W-B', 'away' => '3-7', 'datetime' => '2026-07-02 19:00', 'venue' => 'Vancouver', 'stage' => 'round_of_32', 'timezone' => 'America/Vancouver'], // Match 85

            // Friday, 3 July 2026
            ['home' => 'W-J', 'away' => 'R-H', 'datetime' => '2026-07-03 13:00', 'venue' => 'Miami', 'stage' => 'round_of_32', 'timezone' => 'America/New_York'], // Match 86
            ['home' => 'W-K', 'away' => '3-8', 'datetime' => '2026-07-03 16:00', 'venue' => 'Kansas City', 'stage' => 'round_of_32', 'timezone' => 'America/Chicago'], // Match 87
            ['home' => 'R-D', 'away' => 'R-G', 'datetime' => '2026-07-03 19:00', 'venue' => 'Dallas', 'stage' => 'round_of_32', 'timezone' => 'America/Chicago'], // Match 88


            // ============ ROUND OF 16 (Matches 89-96) ============
            // Participants are winners of 73...88
            
            // Saturday, 4 July 2026
            ['home' => 'W74', 'away' => 'W77', 'datetime' => '2026-07-04 15:00', 'venue' => 'Philadelphia', 'stage' => 'round_of_16', 'timezone' => 'America/New_York'], // Match 89
            ['home' => 'W73', 'away' => 'W75', 'datetime' => '2026-07-04 18:00', 'venue' => 'Houston', 'stage' => 'round_of_16', 'timezone' => 'America/Chicago'], // Match 90

            // Sunday, 5 July 2026
            ['home' => 'W76', 'away' => 'W78', 'datetime' => '2026-07-05 15:00', 'venue' => 'New York', 'stage' => 'round_of_16', 'timezone' => 'America/New_York'], // Match 91
            ['home' => 'W79', 'away' => 'W80', 'datetime' => '2026-07-05 18:00', 'venue' => 'Mexico City', 'stage' => 'round_of_16', 'timezone' => 'America/Mexico_City'], // Match 92

            // Monday, 6 July 2026
            ['home' => 'W83', 'away' => 'W84', 'datetime' => '2026-07-06 15:00', 'venue' => 'Dallas', 'stage' => 'round_of_16', 'timezone' => 'America/Chicago'], // Match 93
            ['home' => 'W81', 'away' => 'W82', 'datetime' => '2026-07-06 18:00', 'venue' => 'Seattle', 'stage' => 'round_of_16', 'timezone' => 'America/Los_Angeles'], // Match 94

            // Tuesday, 7 July 2026
            ['home' => 'W86', 'away' => 'W88', 'datetime' => '2026-07-07 15:00', 'venue' => 'Atlanta', 'stage' => 'round_of_16', 'timezone' => 'America/New_York'], // Match 95
            ['home' => 'W85', 'away' => 'W87', 'datetime' => '2026-07-07 18:00', 'venue' => 'Vancouver', 'stage' => 'round_of_16', 'timezone' => 'America/Vancouver'], // Match 96


            // ============ QUARTER-FINALS (Matches 97-100) ============
            // Participants are winners of 89...96 (Using R16-x codes from TeamSeeder which map to R16 winners)
            // Match 89 Winner = R16-1
            // Match 90 Winner = R16-2
            // ... Match 96 Winner = R16-8
            
            // Thursday, 9 July 2026
            ['home' => 'R16-1', 'away' => 'R16-2', 'datetime' => '2026-07-09 18:00', 'venue' => 'Boston', 'stage' => 'quarter_final', 'timezone' => 'America/New_York'], // Match 97 (W89 v W90)

            // Friday, 10 July 2026
            ['home' => 'R16-5', 'away' => 'R16-6', 'datetime' => '2026-07-10 18:00', 'venue' => 'Los Angeles', 'stage' => 'quarter_final', 'timezone' => 'America/Los_Angeles'], // Match 98 (W93 v W94) -> Wait. User says 98 is W93 vs W94. 
            // W93 is Match 93 Winner -> R16-5. W94 is Match 94 Winner -> R16-6. Correct.

            // Saturday, 11 July 2026
            ['home' => 'R16-3', 'away' => 'R16-4', 'datetime' => '2026-07-11 15:00', 'venue' => 'Miami', 'stage' => 'quarter_final', 'timezone' => 'America/New_York'], // Match 99 (W91 v W92) -> User says W91 v W92.
            // W91 is Match 91 Winner -> R16-3. W92 is Match 92 Winner -> R16-4. Correct.
            
            ['home' => 'R16-7', 'away' => 'R16-8', 'datetime' => '2026-07-11 18:00', 'venue' => 'Kansas City', 'stage' => 'quarter_final', 'timezone' => 'America/Chicago'], // Match 100 (W95 v W96) -> User says W95 v W96.
            // W95 is Match 95 Winner -> R16-7. W96 is Match 96 Winner -> R16-8. Correct.


            // ============ SEMI-FINALS (Matches 101-102) ============
            // Winners of 97...100 -> QF-1...QF-4
            
            // Tuesday, 14 July 2026
            ['home' => 'QF-1', 'away' => 'QF-2', 'datetime' => '2026-07-14 18:00', 'venue' => 'Dallas', 'stage' => 'semi_final', 'timezone' => 'America/Chicago'], // Match 101 (W97 v W98)
            // W97 = QF-1. W98 = QF-2. (Wait. Match 98 is R16-5 v R16-6. Its winner is QF-2? 
            // TeamSeeder says QF-2 is "Winner QF Match 2".
            // Match 98 is "QF Match 2" in sequence (if ordered 97, 98, 99, 100). Yes.

            // Wednesday, 15 July 2026
            ['home' => 'QF-3', 'away' => 'QF-4', 'datetime' => '2026-07-15 18:00', 'venue' => 'Atlanta', 'stage' => 'semi_final', 'timezone' => 'America/New_York'], // Match 102 (W99 v W100).
            // W99 = QF-3. W100 = QF-4. Yes.


            // ============ BRONZE FINAL (July 18) ============
            // Losers of SF matches -> SFL-1, SFL-2
            ['home' => 'SFL-1', 'away' => 'SFL-2', 'datetime' => '2026-07-18 15:00', 'venue' => 'Miami', 'stage' => 'third_place', 'timezone' => 'America/New_York'], // Match 103


            // ============ FINAL (July 19) ============
            // Winners of SF matches -> SF-1, SF-2
            ['home' => 'SF-1', 'away' => 'SF-2', 'datetime' => '2026-07-19 15:00', 'venue' => 'New York', 'stage' => 'final', 'timezone' => 'America/New_York'], // Match 104
        ];
    }
}
