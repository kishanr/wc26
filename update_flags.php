<?php

use App\Models\Team;
use Illuminate\Support\Facades\Http;

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$fifaToIso2 = [
    'KVX' => 'xk', // Kosovo
    'AFG' => 'af', 'ALB' => 'al', 'ALG' => 'dz', 'ASA' => 'as', 'AND' => 'ad', 'ANG' => 'ao', 'AIA' => 'ai', 'ATG' => 'ag', 'ARG' => 'ar', 'ARM' => 'am', 'ARU' => 'aw', 'AUS' => 'au', 'AUT' => 'at', 'AZE' => 'az', 'BAH' => 'bs', 'BHR' => 'bh', 'BAN' => 'bd', 'BRB' => 'bb', 'BLR' => 'by', 'BEL' => 'be', 'BLZ' => 'bz', 'BEN' => 'bj', 'BER' => 'bm', 'BHU' => 'bt', 'BOL' => 'bo', 'BIH' => 'ba', 'BOT' => 'bw', 'BRA' => 'br', 'VGB' => 'vg', 'BRU' => 'bn', 'BUL' => 'bg', 'BFA' => 'bf', 'BDI' => 'bi', 'CAM' => 'kh', 'CMR' => 'cm', 'CAN' => 'ca', 'CPV' => 'cv', 'CAY' => 'ky', 'CTA' => 'cf', 'CHA' => 'td', 'CHI' => 'cl', 'CHN' => 'cn', 'COL' => 'co', 'COM' => 'km', 'CGO' => 'cg', 'COD' => 'cd', 'COK' => 'ck', 'CRC' => 'cr', 'CRO' => 'hr', 'CUB' => 'cu', 'CUW' => 'cw', 'CYP' => 'cy', 'CZE' => 'cz', 'DEN' => 'dk', 'DJI' => 'dj', 'DMA' => 'dm', 'DOM' => 'do', 'ECU' => 'ec', 'EGY' => 'eg', 'SLV' => 'sv', 'ENG' => 'gb-eng', 'EQG' => 'gq', 'ERI' => 'er', 'EST' => 'ee', 'ETH' => 'et', 'FRO' => 'fo', 'FIJ' => 'fj', 'FIN' => 'fi', 'FRA' => 'fr', 'GAB' => 'ga', 'GAM' => 'gm', 'GEO' => 'ge', 'GER' => 'de', 'GHA' => 'gh', 'GIB' => 'gi', 'GRE' => 'gr', 'GRN' => 'gd', 'GUM' => 'gu', 'GUA' => 'gt', 'GUI' => 'gn', 'GNB' => 'gw', 'GUY' => 'gy', 'HAI' => 'ht', 'HON' => 'hn', 'HKG' => 'hk', 'HUN' => 'hu', 'ISL' => 'is', 'IND' => 'in', 'IDN' => 'id', 'IRN' => 'ir', 'IRQ' => 'iq', 'ISR' => 'il', 'ITA' => 'it', 'CIV' => 'ci', 'JAM' => 'jm', 'JPN' => 'jp', 'JOR' => 'jo', 'KAZ' => 'kz', 'KEN' => 'ke', 'PRK' => 'kp', 'KOR' => 'kr', 'KUW' => 'kw', 'KGZ' => 'kg', 'LAO' => 'la', 'LVA' => 'lv', 'LBN' => 'lb', 'LES' => 'ls', 'LBR' => 'lr', 'LBY' => 'ly', 'LIE' => 'li', 'LTU' => 'lt', 'LUX' => 'lu', 'MAC' => 'mo', 'MKD' => 'mk', 'MAD' => 'mg', 'MWI' => 'mw', 'MAS' => 'my', 'MDV' => 'mv', 'MLI' => 'ml', 'MLT' => 'mt', 'MTN' => 'mr', 'MRI' => 'mu', 'MEX' => 'mx', 'MDA' => 'md', 'MNG' => 'mn', 'MNE' => 'me', 'MSR' => 'ms', 'MAR' => 'ma', 'MOZ' => 'mz', 'MYA' => 'mm', 'NAM' => 'na', 'NEP' => 'np', 'NED' => 'nl', 'NCL' => 'nc', 'NZL' => 'nz', 'NCA' => 'ni', 'NIG' => 'ne', 'NGA' => 'ng', 'NIR' => 'gb-nir', 'NOR' => 'no', 'OMA' => 'om', 'PAK' => 'pk', 'PLE' => 'ps', 'PAN' => 'pa', 'PNG' => 'pg', 'PAR' => 'py', 'PER' => 'pe', 'PHI' => 'ph', 'POL' => 'pl', 'POR' => 'pt', 'PUR' => 'pr', 'QAT' => 'qa', 'IRL' => 'ie', 'ROU' => 'ro', 'RUS' => 'ru', 'RWA' => 'rw', 'SKN' => 'kn', 'LCA' => 'lc', 'VIN' => 'vc', 'SAM' => 'ws', 'SMR' => 'sm', 'STP' => 'st', 'KSA' => 'sa', 'SCO' => 'gb-sct', 'SEN' => 'sn', 'SRB' => 'rs', 'SEY' => 'sc', 'SLE' => 'sl', 'SIN' => 'sg', 'SVK' => 'sk', 'SVN' => 'si', 'SOL' => 'sb', 'SOM' => 'so', 'RSA' => 'za', 'ESP' => 'es', 'SRI' => 'lk', 'SDN' => 'sd', 'SUR' => 'sr', 'SWZ' => 'sz', 'SWE' => 'se', 'SUI' => 'ch', 'SYR' => 'sy', 'TAH' => 'pf', 'TJK' => 'tj', 'TAN' => 'tz', 'THA' => 'th', 'TLS' => 'tl', 'TOG' => 'tg', 'TGA' => 'to', 'TRI' => 'tt', 'TUN' => 'tn', 'TUR' => 'tr', 'TKM' => 'tm', 'TCA' => 'tc', 'UGA' => 'ug', 'UKR' => 'ua', 'UAE' => 'ae', 'USA' => 'us', 'URU' => 'uy', 'VIR' => 'vi', 'UZB' => 'uz', 'VAN' => 'vu', 'VEN' => 've', 'VIE' => 'vn', 'WAL' => 'gb-wls', 'YEM' => 'ye', 'ZAM' => 'zm', 'ZIM' => 'zw'
];

$teams = Team::whereNotNull('iso_code')->get();

foreach ($teams as $team) {
    if (isset($fifaToIso2[$team->iso_code])) {
        $code = $fifaToIso2[$team->iso_code];
        $team->flag_url = "https://flagcdn.com/w320/{$code}.png";
        $team->save();
        echo "Updated {$team->getDisplayNameAttribute()} ({$team->iso_code}) -> {$code}\n";
    } elseif ($team->iso_code === 'ENG') {
         $team->flag_url = "https://flagcdn.com/w320/gb-eng.png";
         $team->save();
         echo "Updated England\n";
    } elseif ($team->iso_code === 'SCO') {
         $team->flag_url = "https://flagcdn.com/w320/gb-sct.png";
         $team->save();
         echo "Updated Scotland\n";
    } elseif ($team->iso_code === 'WAL') {
         $team->flag_url = "https://flagcdn.com/w320/gb-wls.png";
         $team->save();
         echo "Updated Wales\n";
    } else {
        echo "Skipped {$team->getDisplayNameAttribute()} ({$team->iso_code})\n";
    }
}

echo "Done.\n";
