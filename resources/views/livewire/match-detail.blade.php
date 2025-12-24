<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <!-- Back Button -->
    <a href="/" class="inline-flex items-center gap-2 text-gray-400 hover:text-white transition mb-6">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Back to Matches
    </a>

    <!-- Match Header -->
    <div class="glass-card p-6 mb-6">
        <!-- Stage & Time -->
        <div class="text-center mb-6">
            <span class="inline-block px-3 py-1 rounded-full bg-[#3a86ff]/20 text-[#3a86ff] text-sm font-medium mb-2">
                {{ $this->getStageName() }}
            </span>
            <div class="text-gray-400 text-sm">
                {{ \Carbon\Carbon::parse($game->start_time)->format('l, F j, Y \a\t H:i') }}
            </div>
        </div>

        <!-- Teams Display -->
        <div class="flex items-center justify-center gap-8 md:gap-16">
            <!-- Home Team -->
            <div class="text-center flex-1">
                <div class="w-20 h-14 mx-auto mb-3 rounded-lg bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center overflow-hidden shadow-lg">
                    @if($game->homeTeam->flag_url)
                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->display_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-bold text-gray-400">{{ $game->homeTeam->iso_code }}</span>
                    @endif
                </div>
                <div class="text-xl font-bold text-white mb-1">
                    {{ $game->homeTeam->display_name }}
                </div>
                <div class="text-sm text-gray-500">{{ $game->homeTeam->confederation ?? '' }}</div>
            </div>

            <!-- Score / VS -->
            <div class="text-center">
                @if($game->status === 'finished' || $game->status === 'live')
                    <div class="text-5xl font-bold text-white tracking-wider">
                        {{ $game->home_score ?? 0 }}
                        <span class="text-gray-600 mx-2">-</span>
                        {{ $game->away_score ?? 0 }}
                    </div>
                    @if($game->home_score_penalties !== null)
                        <div class="text-sm text-gray-500 mt-1">
                            Penalties: {{ $game->home_score_penalties }} - {{ $game->away_score_penalties }}
                        </div>
                    @endif
                    @if($game->status === 'live')
                        <div class="mt-2 inline-block px-3 py-1 rounded-full badge-live text-white text-sm animate-pulse">
                            LIVE
                        </div>
                    @endif
                @else
                    <div class="text-4xl font-bold text-gray-600">VS</div>
                    <div class="text-sm text-gray-500 mt-2">
                        {{ \Carbon\Carbon::parse($game->start_time)->format('H:i') }} local
                    </div>
                @endif
            </div>

            <!-- Away Team -->
            <div class="text-center flex-1">
                <div class="w-20 h-14 mx-auto mb-3 rounded-lg bg-gradient-to-br from-gray-700 to-gray-800 flex items-center justify-center overflow-hidden shadow-lg">
                    @if($game->awayTeam->flag_url)
                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->display_name }}" class="w-full h-full object-cover">
                    @else
                        <span class="text-lg font-bold text-gray-400">{{ $game->awayTeam->iso_code }}</span>
                    @endif
                </div>
                <div class="text-xl font-bold text-white mb-1">
                    {{ $game->awayTeam->getTranslation('name', 'en') }}
                </div>
                <div class="text-sm text-gray-500">{{ $game->awayTeam->confederation ?? '' }}</div>
            </div>
        </div>

        <!-- Stadium Info -->
        <div class="mt-6 pt-4 border-t border-white/10 text-center">
            <div class="flex items-center justify-center gap-2 text-gray-400">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"/>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"/>
                </svg>
                <span>{{ $game->stadium?->name ?? 'Stadium TBD' }}</span>
                <span class="text-gray-600">•</span>
                <span>{{ $game->stadium?->city ?? '' }}, {{ $game->stadium?->country ?? '' }}</span>
            </div>
        </div>
    </div>

    <!-- Team Comparison Section -->
    <div class="grid md:grid-cols-2 gap-6 mb-6">
        <!-- Home Team Details -->
        <div class="glass-card p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-12 h-8 rounded-lg bg-white/5 overflow-hidden flex-shrink-0">
                    @if($game->homeTeam->flag_url)
                        <img src="{{ $game->homeTeam->flag_url }}" alt="{{ $game->homeTeam->display_name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-black text-white">{{ $game->homeTeam->display_name }}</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest">{{ $game->homeTeam->confederation ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- FIFA Ranking -->
                @if($game->homeTeam->fifa_ranking)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ffc300] to-[#ff006e] flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-400">FIFA Ranking</span>
                        </div>
                        <span class="text-2xl font-black text-white">#{{ $game->homeTeam->fifa_ranking }}</span>
                    </div>
                @endif

                <!-- Coach -->
                @if($game->homeTeam->coach)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#00f5d4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-400">Coach</span>
                        </div>
                        <span class="text-sm font-bold text-white">{{ $game->homeTeam->coach }}</span>
                    </div>
                @endif

                <!-- Captain -->
                @if($game->homeTeam->captain)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <span class="text-sm">⭐</span>
                            </div>
                            <span class="text-sm font-bold text-gray-400">Captain</span>
                        </div>
                        <span class="text-sm font-bold text-white">{{ $game->homeTeam->captain }}</span>
                    </div>
                @endif

                <!-- World Cup History -->
                <div class="pt-4 border-t border-white/10">
                    <div class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">World Cup History</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 rounded-lg bg-gradient-to-br from-[#ffc300]/10 to-transparent border border-[#ffc300]/20">
                            <div class="text-2xl font-black text-[#ffc300]">{{ $game->homeTeam->world_cup_titles ?? 0 }}</div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Titles</div>
                        </div>
                        <div class="text-center p-3 rounded-lg bg-white/5 border border-white/5">
                            <div class="text-2xl font-black text-white">{{ $game->homeTeam->world_cup_appearances ?? 0 }}</div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Appearances</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Away Team Details -->
        <div class="glass-card p-6">
            <div class="flex items-center gap-3 mb-6 pb-4 border-b border-white/10">
                <div class="w-12 h-8 rounded-lg bg-white/5 overflow-hidden flex-shrink-0">
                    @if($game->awayTeam->flag_url)
                        <img src="{{ $game->awayTeam->flag_url }}" alt="{{ $game->awayTeam->display_name }}" class="w-full h-full object-cover">
                    @endif
                </div>
                <div class="flex-1">
                    <h3 class="text-xl font-black text-white">{{ $game->awayTeam->display_name }}</h3>
                    <p class="text-xs text-gray-500 uppercase tracking-widest">{{ $game->awayTeam->confederation ?? 'N/A' }}</p>
                </div>
            </div>

            <div class="space-y-4">
                <!-- FIFA Ranking -->
                @if($game->awayTeam->fifa_ranking)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-gradient-to-br from-[#ffc300] to-[#ff006e] flex items-center justify-center">
                                <svg class="w-4 h-4 text-white" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-400">FIFA Ranking</span>
                        </div>
                        <span class="text-2xl font-black text-white">#{{ $game->awayTeam->fifa_ranking }}</span>
                    </div>
                @endif

                <!-- Coach -->
                @if($game->awayTeam->coach)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <svg class="w-4 h-4 text-[#00f5d4]" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <span class="text-sm font-bold text-gray-400">Coach</span>
                        </div>
                        <span class="text-sm font-bold text-white">{{ $game->awayTeam->coach }}</span>
                    </div>
                @endif

                <!-- Captain -->
                @if($game->awayTeam->captain)
                    <div class="flex items-center justify-between p-3 rounded-lg bg-white/5 border border-white/5">
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-white/10 flex items-center justify-center">
                                <span class="text-sm">⭐</span>
                            </div>
                            <span class="text-sm font-bold text-gray-400">Captain</span>
                        </div>
                        <span class="text-sm font-bold text-white">{{ $game->awayTeam->captain }}</span>
                    </div>
                @endif

                <!-- World Cup History -->
                <div class="pt-4 border-t border-white/10">
                    <div class="text-xs font-black text-gray-500 uppercase tracking-widest mb-3">World Cup History</div>
                    <div class="grid grid-cols-2 gap-3">
                        <div class="text-center p-3 rounded-lg bg-gradient-to-br from-[#ffc300]/10 to-transparent border border-[#ffc300]/20">
                            <div class="text-2xl font-black text-[#ffc300]">{{ $game->awayTeam->world_cup_titles ?? 0 }}</div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Titles</div>
                        </div>
                        <div class="text-center p-3 rounded-lg bg-white/5 border border-white/5">
                            <div class="text-2xl font-black text-white">{{ $game->awayTeam->world_cup_appearances ?? 0 }}</div>
                            <div class="text-[10px] font-bold text-gray-500 uppercase tracking-wider mt-1">Appearances</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Prediction Card -->
    @if($game->status === 'scheduled')
        <div class="glass-card p-6 mb-6 {{ $canPredict ? 'glow-cyan' : '' }}">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <span class="text-2xl">🎯</span>
                Make Your Prediction
            </h3>

            @if(!$canPredict)
                <div class="bg-red-500/10 border border-red-500/30 rounded-lg p-4 text-red-400 text-center">
                    <p>Predictions are closed for this match</p>
                    <p class="text-sm text-red-500/70 mt-1">Deadline: 1 hour before kickoff</p>
                </div>
            @else
                <!-- AI Prediction Suggestion -->
                @if($aiPrediction)
                    <div class="mb-6 p-5 rounded-xl bg-gradient-to-r from-[#3a86ff]/10 to-[#00f5d4]/10 border border-[#00f5d4]/30 relative overflow-hidden">
                        <!-- Glow effect -->
                        <div class="absolute inset-0 bg-gradient-to-r from-[#3a86ff]/5 to-[#00f5d4]/5 blur-xl"></div>
                        
                        <div class="relative z-10">
                            <div class="flex items-center gap-3 mb-4">
                                <div class="w-10 h-10 rounded-xl bg-gradient-to-br from-[#3a86ff] to-[#00f5d4] flex items-center justify-center shadow-lg">
                                    <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9.663 17h4.673M12 3v1m6.364 1.636l-.707.707M21 12h-1M4 12H3m3.343-5.657l-.707-.707m2.828 9.9a5 5 0 117.072 0l-.548.547A3.374 3.374 0 0014 18.469V19a2 2 0 11-4 0v-.531c0-.895-.356-1.754-.988-2.386l-.548-.547z" />
                                    </svg>
                                </div>
                                <div class="flex-1">
                                    <h4 class="text-sm font-black text-white uppercase tracking-widest">AI Prediction</h4>
                                    <p class="text-xs text-gray-500">Based on FIFA ranking, WC history & more</p>
                                </div>
                                <div class="text-right">
                                    <div class="text-xs font-bold text-gray-500 uppercase tracking-wider">Confidence</div>
                                    <div class="text-lg font-black text-[#00f5d4]">{{ $aiPrediction->confidence_percentage }}%</div>
                                </div>
                            </div>
                            
                            <div class="flex items-center justify-center gap-8 mb-4">
                                <div class="text-center">
                                    <div class="text-4xl font-black text-white mb-1">{{ $aiPrediction->predicted_home_score }}</div>
                                    <div class="text-xs text-gray-500 font-bold">{{ $game->homeTeam->display_name }}</div>
                                </div>
                                <div class="text-2xl font-bold text-gray-600">-</div>
                                <div class="text-center">
                                    <div class="text-4xl font-black text-white mb-1">{{ $aiPrediction->predicted_away_score }}</div>
                                    <div class="text-xs text-gray-500 font-bold">{{ $game->awayTeam->display_name }}</div>
                                </div>
                            </div>
                            
                            <button 
                                x-data 
                                @click="$wire.homeScore = {{ $aiPrediction->predicted_home_score }}; $wire.awayScore = {{ $aiPrediction->predicted_away_score }}"
                                class="w-full py-2.5 text-sm font-bold text-[#00f5d4] hover:bg-[#00f5d4]/10 rounded-lg transition-all border border-[#00f5d4]/20 hover:border-[#00f5d4]/40"
                            >
                                ✨ Use AI Prediction
                            </button>
                        </div>
                    </div>
                @endif

                <div x-data="{ 
                    homeScore: @entangle('homeScore'), 
                    awayScore: @entangle('awayScore'),
                    setPick(h, a) {
                        this.homeScore = h;
                        this.awayScore = a;
                    }
                }" class="space-y-6 mb-8">
                    <!-- Score Input -->
                    <div class="glass-card p-6 md:p-8">
                        <div class="flex items-center justify-center gap-12">
                            <!-- Home Team Input -->
                            <div class="text-center">
                                <div class="w-20 h-14 mx-auto rounded-lg overflow-hidden shadow-lg mb-4 ring-2 ring-white/10 bg-white/5">
                                    <img src="{{ $game->homeTeam->flag_url }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex items-center gap-4">
                                    <button @click="if(homeScore > 0) homeScore--" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <div class="text-6xl font-black text-white w-20" x-text="homeScore"></div>
                                    <button @click="homeScore++" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <div class="mt-2 text-sm font-semibold text-gray-400">{{ $game->homeTeam->display_name }}</div>
                            </div>

                            <div class="text-2xl font-bold text-gray-600 self-center">-</div>

                            <!-- Away Team Input -->
                            <div class="text-center">
                                <div class="w-20 h-14 mx-auto rounded-lg overflow-hidden shadow-lg mb-4 ring-2 ring-white/10 bg-white/5">
                                    <img src="{{ $game->awayTeam->flag_url }}" class="w-full h-full object-cover">
                                </div>
                                <div class="flex items-center gap-4">
                                    <button @click="if(awayScore > 0) awayScore--" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"/></svg>
                                    </button>
                                    <div class="text-6xl font-black text-white w-20" x-text="awayScore"></div>
                                    <button @click="awayScore++" class="w-10 h-10 rounded-full bg-white/5 hover:bg-white/10 flex items-center justify-center text-white transition">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/></svg>
                                    </button>
                                </div>
                                <div class="mt-2 text-sm font-semibold text-gray-400">{{ $game->awayTeam->display_name }}</div>
                            </div>
                        </div>
                    </div>

                    <!-- Quick Picks -->
                    <div>
                        <div class="text-xs font-bold text-gray-500 uppercase tracking-widest mb-3 px-1">Quick Picks</div>
                        <div class="grid grid-cols-4 sm:grid-cols-6 gap-2">
                            @foreach([
                                [1, 0], [2, 0], [2, 1], [0, 0],
                                [1, 1], [2, 2], [0, 1], [0, 2],
                                [1, 2], [3, 0], [3, 1], [1, 3]
                            ] as $pick)
                                <button 
                                    @click="setPick({{ $pick[0] }}, {{ $pick[1] }})"
                                    class="glass-card py-2 text-sm font-bold hover:border-[#00f5d4]/50 transition text-gray-400"
                                    :class="homeScore == {{ $pick[0] }} && awayScore == {{ $pick[1] }} ? 'border-[#00f5d4] bg-[#00f5d4]/10 text-white' : ''"
                                >
                                    {{ $pick[0] }}-{{ $pick[1] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Quick Picks Removed in favor of Hybrid Slider -->

                <!-- Submit Button -->
                @auth
                    <button 
                        wire:click="savePrediction"
                        class="w-full btn-primary text-center py-4 text-lg"
                    >
                        {{ $userPrediction ? '✏️ Update Prediction' : '🎯 Submit Prediction' }}
                    </button>
                @else
                    <a href="/login" class="block w-full btn-secondary text-center py-4 text-lg">
                        Sign in to Predict
                    </a>
                @endauth

                @if($message)
                    <div class="mt-4 text-center text-[#00f5d4]">{{ $message }}</div>
                @endif

                @if($userPrediction)
                    <div class="mt-4 text-center text-gray-500 text-sm">
                        Your current prediction: {{ $userPrediction->home_score }} - {{ $userPrediction->away_score }}
                    </div>
                @endif
            @endif
        </div>
    @endif

    <!-- Match Result (if finished) -->
    @if($game->status === 'finished' && $userPrediction)
        <div class="glass-card p-6 mb-6">
            <h3 class="text-xl font-bold text-white mb-4">Your Prediction Result</h3>
            <div class="grid grid-cols-2 gap-4">
                <div class="text-center p-4 rounded-lg bg-white/5">
                    <div class="text-gray-400 text-sm mb-1">You Predicted</div>
                    <div class="text-2xl font-bold text-white">
                        {{ $userPrediction->home_score }} - {{ $userPrediction->away_score }}
                    </div>
                </div>
                <div class="text-center p-4 rounded-lg bg-white/5">
                    <div class="text-gray-400 text-sm mb-1">Points Earned</div>
                    <div class="text-2xl font-bold text-[#ffc300]">
                        +{{ $userPrediction->points_earned ?? 0 }}
                    </div>
                </div>
            </div>
        </div>
    @endif
    @if($relatedMatches->isNotEmpty())
        <div class="glass-card p-6 mt-6">
            <h3 class="text-lg font-bold text-white mb-4 flex items-center gap-2">
                <span class="w-1.5 h-6 bg-[#00f5d4] rounded-full"></span>
                Related Matches
            </h3>
            <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
                @foreach($relatedMatches as $match)
                    <x-match-card :match="$match" />
                @endforeach
            </div>
        </div>
    @endif
</div>
