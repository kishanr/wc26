<?php

namespace Database\Seeders;

use App\Models\Stadium;
use Illuminate\Database\Seeder;

class StadiumSeeder extends Seeder
{
    /**
     * Official WC26 Venues - 16 stadiums across USA, Mexico, and Canada
     */
    public function run(): void
    {
        $stadiums = [
            // USA (11 venues)
            [
                'name' => 'MetLife Stadium',
                'city' => 'East Rutherford',
                'country' => 'USA',
                'capacity' => 82500,
                'latitude' => 40.8128,
                'longitude' => -74.0742,
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'AT&T Stadium',
                'city' => 'Arlington',
                'country' => 'USA',
                'capacity' => 80000,
                'latitude' => 32.7480,
                'longitude' => -97.0929,
                'timezone' => 'America/Chicago',
            ],
            [
                'name' => 'SoFi Stadium',
                'city' => 'Inglewood',
                'country' => 'USA',
                'capacity' => 70240,
                'latitude' => 33.9535,
                'longitude' => -118.3392,
                'timezone' => 'America/Los_Angeles',
            ],
            [
                'name' => 'Hard Rock Stadium',
                'city' => 'Miami Gardens',
                'country' => 'USA',
                'capacity' => 65326,
                'latitude' => 25.9580,
                'longitude' => -80.2389,
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'NRG Stadium',
                'city' => 'Houston',
                'country' => 'USA',
                'capacity' => 72220,
                'latitude' => 29.6847,
                'longitude' => -95.4107,
                'timezone' => 'America/Chicago',
            ],
            [
                'name' => 'Mercedes-Benz Stadium',
                'city' => 'Atlanta',
                'country' => 'USA',
                'capacity' => 71000,
                'latitude' => 33.7553,
                'longitude' => -84.4006,
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'Lincoln Financial Field',
                'city' => 'Philadelphia',
                'country' => 'USA',
                'capacity' => 69796,
                'latitude' => 39.9008,
                'longitude' => -75.1675,
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'Lumen Field',
                'city' => 'Seattle',
                'country' => 'USA',
                'capacity' => 69000,
                'latitude' => 47.5952,
                'longitude' => -122.3316,
                'timezone' => 'America/Los_Angeles',
            ],
            [
                'name' => 'Gillette Stadium',
                'city' => 'Foxborough',
                'country' => 'USA',
                'capacity' => 65878,
                'latitude' => 42.0909,
                'longitude' => -71.2643,
                'timezone' => 'America/New_York',
            ],
            [
                'name' => 'Arrowhead Stadium',
                'city' => 'Kansas City',
                'country' => 'USA',
                'capacity' => 76416,
                'latitude' => 39.0489,
                'longitude' => -94.4839,
                'timezone' => 'America/Chicago',
            ],
            [
                'name' => 'Levi\'s Stadium',
                'city' => 'Santa Clara',
                'country' => 'USA',
                'capacity' => 68500,
                'latitude' => 37.4033,
                'longitude' => -121.9694,
                'timezone' => 'America/Los_Angeles',
            ],

            // Mexico (3 venues)
            [
                'name' => 'Estadio Azteca',
                'city' => 'Mexico City',
                'country' => 'MEX',
                'capacity' => 87523,
                'latitude' => 19.3029,
                'longitude' => -99.1505,
                'timezone' => 'America/Mexico_City',
            ],
            [
                'name' => 'Estadio Akron',
                'city' => 'Guadalajara',
                'country' => 'MEX',
                'capacity' => 49850,
                'latitude' => 20.6820,
                'longitude' => -103.4624,
                'timezone' => 'America/Mexico_City',
            ],
            [
                'name' => 'Estadio BBVA',
                'city' => 'Monterrey',
                'country' => 'MEX',
                'capacity' => 53500,
                'latitude' => 25.6699,
                'longitude' => -100.2439,
                'timezone' => 'America/Monterrey',
            ],

            // Canada (2 venues)
            [
                'name' => 'BMO Field',
                'city' => 'Toronto',
                'country' => 'CAN',
                'capacity' => 45736,
                'latitude' => 43.6332,
                'longitude' => -79.4185,
                'timezone' => 'America/Toronto',
            ],
            [
                'name' => 'BC Place',
                'city' => 'Vancouver',
                'country' => 'CAN',
                'capacity' => 54500,
                'latitude' => 49.2768,
                'longitude' => -123.1116,
                'timezone' => 'America/Vancouver',
            ],
        ];

        foreach ($stadiums as $stadium) {
            Stadium::create($stadium);
        }

        $this->command->info('✅ Created ' . count($stadiums) . ' stadiums for WC26');
    }
}
