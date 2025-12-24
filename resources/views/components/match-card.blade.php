@props(['match', 'featured' => false])

<div>
    <a 
        href="{{ route('match.show', $match) }}"
        class="block glass-card match-card p-4 {{ $featured ? 'border-[#00f5d4]/30' : '' }}"
    >
        <!-- Header: Stage & Time -->
        <div class="flex items-center justify-between mb-4">
            <span class="text-xs font-medium text-gray-400">{{ $match->stage_name }}</span>
            <span class="text-xs px-2 py-1 rounded-full {{ $match->status_badge['class'] }} text-white">
                {{ $match->status_badge['text'] }}
            </span>
        </div>

        <!-- Teams -->
        <div class="flex items-center justify-between gap-4">
            <!-- Home Team -->
            <div class="flex-1 text-center min-w-0">
                <div class="w-12 h-8 mx-auto mb-2 rounded bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center overflow-hidden">
                    @if($match->homeTeam->flag_url)
                        <img src="{{ $match->homeTeam->flag_url }}" alt="{{ $match->homeTeam->display_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs font-bold text-gray-400">{{ $match->homeTeam->iso_code }}</span>
                    @endif
                </div>
                <div class="text-sm font-semibold text-white truncate">
                    {{ $match->homeTeam->display_name }}
                </div>
                <div class="text-xs text-gray-500">{{ $match->homeTeam->iso_code }}</div>
            </div>

            <!-- Score / VS -->
            <div class="flex-shrink-0 text-center px-4">
                @if($match->status === 'finished' || $match->status === 'live')
                    <div class="score-display text-3xl">
                        {{ $match->home_score ?? 0 }}
                        <span class="text-gray-600 mx-1">-</span>
                        {{ $match->away_score ?? 0 }}
                    </div>
                    @if($match->home_score_penalties !== null)
                        <div class="text-xs text-gray-500">
                            ({{ $match->home_score_penalties }} - {{ $match->away_score_penalties }})
                        </div>
                    @endif
                @else
                    <div class="text-xl font-bold text-gray-600">VS</div>
                    <div class="text-xs text-gray-500 mt-1">
                        {{ \Carbon\Carbon::parse($match->start_time)->format('H:i') }}
                    </div>
                @endif
            </div>

            <!-- Away Team -->
            <div class="flex-1 text-center min-w-0">
                <div class="w-12 h-8 mx-auto mb-2 rounded bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center overflow-hidden">
                    @if($match->awayTeam->flag_url)
                        <img src="{{ $match->awayTeam->flag_url }}" alt="{{ $match->awayTeam->display_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-xs font-bold text-gray-400">{{ $match->awayTeam->iso_code }}</span>
                    @endif
                </div>
                <div class="text-sm font-semibold text-white truncate">
                    {{ $match->awayTeam->display_name }}
                </div>
                <div class="text-xs text-gray-500">{{ $match->awayTeam->iso_code }}</div>
            </div>
        </div>

        <!-- Footer: Stadium -->
        <div class="mt-4 pt-3 border-t border-white/5 flex items-center justify-between text-xs text-gray-500">
            <div class="flex items-center gap-1.5">
                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                {{ $match->stadium?->name ?? 'TBD' }}
            </div>
            <div>
                {{ \Carbon\Carbon::parse($match->start_time)->format('M j') }}
            </div>
        </div>
    </a>
</div>
