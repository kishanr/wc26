<div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
    <div class="flex items-center justify-between mb-8">
        <h1 class="text-3xl font-bold text-white">My Predictions</h1>
        <a href="/" class="btn-secondary text-sm">View All Matches</a>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 bg-green-500/10 border border-green-500/50 rounded-lg text-green-400 text-sm">
            {{ session('success') }}
        </div>
    @endif

    @if (session()->has('error'))
        <div class="mb-6 p-4 bg-red-500/10 border border-red-500/50 rounded-lg text-red-400 text-sm">
            {{ session('error') }}
        </div>
    @endif

    <!-- 1. Upcoming Predictions (Already Predicted) -->
    <section class="mb-12">
        <h2 class="text-xl font-semibold text-gray-300 mb-4 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-blue-500 rounded-full"></span>
            Upcoming Matches
        </h2>
        @if($upcoming->isEmpty())
            <div class="glass-card p-8 text-center text-gray-500">
                <p>No upcoming predictions found.</p>
                <a href="/" class="text-blue-400 hover:underline mt-2 inline-block">Predict some matches →</a>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($upcoming as $prediction)
                    <a href="{{ route('match.show', $prediction->game->slug) }}" class="glass-card p-4 flex items-center justify-between hover:border-blue-500/30 transition group">
                        <div class="flex items-center gap-6">
                            <div class="text-center w-12 flex-shrink-0">
                                <span class="text-xs text-gray-500 block uppercase">{{ $prediction->game->stage_name }}</span>
                                <span class="text-[10px] text-gray-600 block">{{ $prediction->game->start_time->format('M d') }}</span>
                                <span class="text-xs font-bold text-white">{{ $prediction->game->start_time->format('H:i') }}</span>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2 min-w-[120px] justify-end">
                                    <span class="text-white font-medium text-right truncate">{{ $prediction->game->homeTeam->display_name }}</span>
                                    <img src="{{ $prediction->game->homeTeam->flag_url }}" class="w-8 h-5 rounded shadow">
                                </div>
                                <div class="px-3 py-1 rounded bg-white/5 text-blue-400 font-bold min-w-[60px] text-center">
                                    {{ $prediction->home_score }} - {{ $prediction->away_score }}
                                </div>
                                <div class="flex items-center gap-2 min-w-[120px] justify-start">
                                    <img src="{{ $prediction->game->awayTeam->flag_url }}" class="w-8 h-5 rounded shadow">
                                    <span class="text-white font-medium truncate">{{ $prediction->game->awayTeam->display_name }}</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="flex items-center gap-4">
                            <span class="text-xs text-gray-500 group-hover:text-blue-400">Edit Prediction →</span>
                        </div>
                    </a>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 2. Past Predictions (Results) -->
    <section class="mb-12">
        <h2 class="text-xl font-semibold text-gray-300 mb-4 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-[#00f5d4] rounded-full"></span>
            Past Results
        </h2>
        @if($past->isEmpty())
            <div class="glass-card p-8 text-center text-gray-500">
                <p>No past predictions yet.</p>
            </div>
        @else
            <div class="grid gap-4">
                @foreach($past as $prediction)
                    <div class="glass-card p-4 flex items-center justify-between">
                        <div class="flex items-center gap-6">
                            <div class="text-center w-12 flex-shrink-0">
                                <span class="text-[10px] text-gray-500 block uppercase truncate">{{ $prediction->game->stage_name }}</span>
                                <span class="text-xs text-gray-500 block">{{ $prediction->game->start_time->format('M d') }}</span>
                                <span class="text-sm font-bold text-gray-400">FT</span>
                            </div>
                            
                            <div class="flex items-center gap-4">
                                <div class="flex items-center gap-2 {{ $prediction->game->home_score > $prediction->game->away_score ? 'font-bold' : '' }} min-w-[80px] justify-end">
                                    <img src="{{ $prediction->game->homeTeam->flag_url }}" class="w-8 h-5 rounded shadow">
                                    <span class="text-white">{{ $prediction->game->homeTeam->iso_code }}</span>
                                </div>
                                <div class="flex flex-col items-center px-4">
                                    <div class="text-lg font-bold text-white whitespace-nowrap">
                                        {{ $prediction->game->home_score }} - {{ $prediction->game->away_score }}
                                    </div>
                                    <div class="text-[10px] text-gray-500 uppercase tracking-tighter">Actual Result</div>
                                </div>
                                <div class="flex items-center gap-2 {{ $prediction->game->away_score > $prediction->game->home_score ? 'font-bold' : '' }} min-w-[80px] justify-start">
                                    <span class="text-white">{{ $prediction->game->awayTeam->iso_code }}</span>
                                    <img src="{{ $prediction->game->awayTeam->flag_url }}" class="w-8 h-5 rounded shadow">
                                </div>
                            </div>
                        </div>

                        <div class="flex items-center gap-8">
                            <div class="text-center">
                                <div class="text-xs text-gray-500 uppercase mb-1">Predicted</div>
                                <div class="text-sm font-bold text-gray-400">
                                    {{ $prediction->home_score }} - {{ $prediction->away_score }}
                                </div>
                            </div>
                            <div class="text-center px-4 py-2 rounded-lg bg-[#00f5d4]/10 border border-[#00f5d4]/20">
                                <div class="text-[10px] text-[#00f5d4] uppercase font-bold mb-1">Points</div>
                                <div class="text-xl font-black text-[#00f5d4]">
                                    +{{ $prediction->points_earned }}
                                </div>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </section>

    <!-- 3. Unpredicted Matches (Action Needed) -->
    @if(!$unpredictedGrouped->isEmpty())
    <section class="mb-12">
        <h2 class="text-xl font-semibold text-gray-300 mb-6 flex items-center gap-2">
            <span class="w-1.5 h-6 bg-yellow-500 rounded-full"></span>
            Action Needed: Needs Prediction
        </h2>
        
        @foreach($unpredictedGrouped as $stage => $games)
            <div class="mb-8">
                <h3 class="text-sm font-bold text-gray-500 uppercase tracking-widest mb-3 px-1">{{ $stage }}</h3>
                <div class="grid gap-4">
                    @foreach($games as $game)
                        <div x-data="{ home: 0, away: 0 }" class="glass-card p-4 flex flex-col md:flex-row items-center justify-between gap-6 hover:border-yellow-500/30 transition group">
                            <div class="flex items-center gap-6 w-full md:w-auto">
                                <div class="text-center w-12 flex-shrink-0">
                                    <span class="text-xs text-gray-500 block">{{ $game->start_time->format('M d') }}</span>
                                    <span class="text-sm font-bold text-white">{{ $game->start_time->format('H:i') }}</span>
                                </div>
                                
                                <div class="flex items-center gap-4 flex-grow">
                                    <div class="flex items-center gap-2 min-w-[120px] justify-end">
                                        <span class="text-white font-medium text-right truncate">{{ $game->homeTeam->display_name }}</span>
                                        <img src="{{ $game->homeTeam->flag_url }}" class="w-8 h-5 rounded shadow flex-shrink-0">
                                    </div>
                                    
                                    <div class="text-gray-600 font-bold">VS</div>
                                    
                                    <div class="flex items-center gap-2 min-w-[120px] justify-start">
                                        <img src="{{ $game->awayTeam->flag_url }}" class="w-8 h-5 rounded shadow flex-shrink-0">
                                        <span class="text-white font-medium truncate">{{ $game->awayTeam->display_name }}</span>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="flex items-center gap-4 w-full md:w-auto justify-end">
                                <!-- Score Controls -->
                                <div class="flex items-center bg-black/40 rounded-lg p-1 border border-white/5">
                                    <div class="flex items-center gap-2 px-2">
                                        <button @click="if(home > 0) home--" class="w-6 h-6 flex items-center justify-center rounded bg-white/5 hover:bg-white/10 text-white">-</button>
                                        <span class="w-4 text-center font-bold text-[#00f5d4]" x-text="home">0</span>
                                        <button @click="home++" class="w-6 h-6 flex items-center justify-center rounded bg-white/5 hover:bg-white/10 text-white">+</button>
                                    </div>
                                    <div class="text-gray-600 font-black px-1">:</div>
                                    <div class="flex items-center gap-2 px-2">
                                        <button @click="if(away > 0) away--" class="w-6 h-6 flex items-center justify-center rounded bg-white/5 hover:bg-white/10 text-white">-</button>
                                        <span class="w-4 text-center font-bold text-[#00f5d4]" x-text="away">0</span>
                                        <button @click="away++" class="w-6 h-6 flex items-center justify-center rounded bg-white/5 hover:bg-white/10 text-white">+</button>
                                    </div>
                                </div>

                                <button 
                                    wire:click="fastPredict({{ $game->id }}, home, away)"
                                    class="bg-[#00f5d4] hover:bg-[#00f5d4]/80 text-black font-bold py-2 px-4 rounded-lg transform active:scale-95 transition text-sm"
                                >
                                    Save
                                </button>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @endforeach
    </section>
    @endif
</div>
