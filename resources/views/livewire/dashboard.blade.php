<div x-data="matchDashboard({{ Js::from($allMatches) }})">
    <!-- Hero Section -->
    <section class="relative overflow-hidden pt-12 pb-20">
        <div class="absolute inset-0 bg-gradient-to-br from-[#00f5d4]/10 via-transparent to-[#ff006e]/10"></div>
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center mb-10">
                <h1 class="text-5xl md:text-7xl font-black mb-4 tracking-tight">
                    <span class="text-gradient">WC26 EXPLORER</span>
                </h1>
                <p class="text-xl text-gray-400 font-medium">USA, Mexico & Canada • June 11 - July 19, 2026</p>
            </div>

            <!-- Premium Search Bar -->
            <div class="max-w-2xl mx-auto mb-12">
                <div class="relative group">
                    <!-- Glow effect on focus -->
                    <div class="absolute -inset-1 bg-gradient-to-r from-[#00f5d4]/20 via-[#3a86ff]/20 to-[#ff006e]/20 rounded-3xl blur-xl opacity-0 group-focus-within:opacity-100 transition-opacity duration-500"></div>
                    
                    <!-- Search container -->
                    <div class="relative glass-card border-white/10 rounded-2xl overflow-hidden">
                        <div class="flex items-center">
                            <!-- Search icon -->
                            <div class="pl-6 pr-3 text-gray-500 group-focus-within:text-[#00f5d4] transition-colors duration-300">
                                <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z" />
                                </svg>
                            </div>
                            
                            <!-- Input field -->
                            <input 
                                type="text" 
                                x-model="search"
                                placeholder="Search matches, teams, cities, or dates..." 
                                class="flex-1 bg-transparent border-none text-white placeholder-gray-600 focus:outline-none focus:ring-0 py-4 pr-4 text-base font-medium"
                                @keydown.escape="search = ''"
                            >
                            
                            <!-- Clear button -->
                            <template x-if="search">
                                <button 
                                    @click="search = ''" 
                                    class="mr-3 p-2 rounded-lg text-gray-500 hover:text-white hover:bg-white/10 transition-all duration-200"
                                >
                                    <svg class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2.5">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </template>
                            
                            <!-- Search badge -->
                            <div class="hidden sm:flex items-center gap-1.5 mr-4 px-3 py-1.5 bg-white/5 rounded-lg border border-white/10">
                                <svg class="h-3 w-3 text-[#00f5d4]" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd" />
                                </svg>
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Smart</span>
                            </div>
                        </div>
                    </div>
                </div>
                
                <!-- Quick search suggestions -->
                <div class="mt-5 flex flex-wrap items-center justify-center gap-3">
                    <span class="text-[10px] font-bold text-gray-600 uppercase tracking-[0.2em]">Quick Search:</span>
                    <button @click="search = 'June 16'" class="px-3 py-1.5 text-xs font-bold text-gray-400 hover:text-[#00f5d4] bg-white/5 hover:bg-white/10 rounded-lg border border-white/5 hover:border-[#00f5d4]/30 transition-all duration-200">
                        June 16
                    </button>
                    <button @click="search = 'Texas'" class="px-3 py-1.5 text-xs font-bold text-gray-400 hover:text-[#00f5d4] bg-white/5 hover:bg-white/10 rounded-lg border border-white/5 hover:border-[#00f5d4]/30 transition-all duration-200">
                        Texas
                    </button>
                    <button @click="search = 'Miami'" class="px-3 py-1.5 text-xs font-bold text-gray-400 hover:text-[#00f5d4] bg-white/5 hover:bg-white/10 rounded-lg border border-white/5 hover:border-[#00f5d4]/30 transition-all duration-200">
                        Miami
                    </button>
                </div>
            </div>

            <!-- Stats Row -->
            <div class="grid grid-cols-3 gap-6 max-w-xl mx-auto">
                <div class="glass-card p-4 text-center border-t-2 border-[#00f5d4]/20">
                    <div class="text-3xl font-black text-white">{{ $stats['total'] }}</div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Matches</div>
                </div>
                <div class="glass-card p-4 text-center border-t-2 border-[#ffc300]/20">
                    <div class="text-3xl font-black text-white">{{ $stats['teams'] }}</div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Teams</div>
                </div>
                <div class="glass-card p-4 text-center border-t-2 border-[#ff006e]/20">
                    <div class="text-3xl font-black text-white">16</div>
                    <div class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">Stadiums</div>
                </div>
            </div>
        </div>
    </section>

    <!-- Filters -->
    <div class="relative">
        <section class="sticky top-16 z-40 backdrop-blur-xl bg-[#0a0e1a]/80 border-y border-white/5 py-3">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center gap-4 overflow-x-auto no-scrollbar">
                    <template x-for="s in stages" :key="s.id">
                        <button 
                            @click="setStage(s.id)" 
                            class="px-4 py-2 text-xs font-bold rounded-xl transition-all whitespace-nowrap uppercase tracking-wider"
                            :class="stage === s.id ? 'bg-[#00f5d4] text-[#0a0e1a] shadow-[0_0_20px_rgba(0,245,212,0.3)]' : 'bg-white/5 text-gray-400 hover:bg-white/10'"
                            x-text="s.label"
                        ></button>
                    </template>
                    
                    <div x-show="stage === 'group' || stage === 'all'" class="flex items-center gap-2 ml-4 pl-4 border-l border-white/10" x-transition>
                        <template x-for="g in groups" :key="g">
                            <button 
                                @click="setGroup(g)" 
                                class="w-8 h-8 text-[10px] font-black rounded-lg transition-all"
                                :class="group === g ? 'bg-[#3a86ff] text-white' : 'bg-white/5 text-gray-500 hover:bg-white/10'"
                                x-text="g"
                            ></button>
                        </template>
                    </div>
                </div>
            </div>
        </section>

        <!-- Matches List -->
        <section class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 min-h-[600px]">
            <template x-for="(group, dateKey) in groupedMatches" :key="dateKey">
                <div class="mb-12">
                    <h3 class="text-xl font-black text-white mb-6 flex items-center gap-4">
                        <span x-text="formatGroupDate(dateKey)"></span>
                        <div class="flex-grow h-px bg-gradient-to-r from-white/10 to-transparent"></div>
                        <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest" x-text="group.length + ' matches'"></span>
                    </h3>
                    <div class="grid gap-6 md:grid-cols-2 lg:grid-cols-3">
                        <template x-for="match in group" :key="match.id">
                            <a :href="'/match/' + match.slug" class="block glass-card match-card p-5 hover:border-[#00f5d4]/40 transition-all transform hover:-translate-y-1 group">
                                 <!-- Header -->
                                 <div class="flex items-center justify-between mb-5">
                                    <span class="text-[10px] font-black text-gray-500 uppercase tracking-[0.15em]" x-text="match.stage_name"></span>
                                    <span class="text-[9px] px-2.5 py-1 rounded-full font-black uppercase tracking-widest" :class="match.status_badge.class" x-text="match.status_badge.text"></span>
                                </div>

                                <!-- Teams -->
                                <div class="flex items-center justify-between gap-4">
                                    <div class="flex-1">
                                        <div class="w-12 h-8 rounded-lg bg-white/5 flex items-center justify-center overflow-hidden mb-3 group-hover:scale-105 transition-transform">
                                            <template x-if="match.home_team.flag_url">
                                                <img :src="match.home_team.flag_url" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                        <div class="text-sm font-black text-white leading-tight" x-text="match.home_team.display_name"></div>
                                    </div>

                                    <!-- VS / Score -->
                                    <div class="flex-shrink-0 text-center">
                                        <template x-if="match.status === 'finished' || match.status === 'live'">
                                            <div class="text-3xl font-black text-white tabular-nums">
                                                <span x-text="match.home_score"></span><span class="mx-1 opacity-40">:</span><span x-text="match.away_score"></span>
                                            </div>
                                        </template>
                                        <template x-if="match.status !== 'finished' && match.status !== 'live'">
                                            <div class="bg-white/5 rounded-2xl px-4 py-2 border border-white/5">
                                                <div class="text-[10px] font-black text-gray-500 uppercase tracking-widest">Starts</div>
                                                <div class="text-sm font-black text-[#00f5d4]" x-text="formatTime(match.start_time)"></div>
                                            </div>
                                        </template>
                                    </div>

                                    <div class="flex-1 text-right">
                                        <div class="w-12 h-8 rounded-lg bg-white/5 flex items-center justify-center overflow-hidden mb-3 ml-auto group-hover:scale-105 transition-transform">
                                            <template x-if="match.away_team.flag_url">
                                                <img :src="match.away_team.flag_url" class="w-full h-full object-cover">
                                            </template>
                                        </div>
                                        <div class="text-sm font-black text-white leading-tight" x-text="match.away_team.display_name"></div>
                                    </div>
                                </div>
                                
                                <!-- Footer -->
                                 <div class="mt-6 pt-4 border-t border-white/5 flex items-center justify-between">
                                    <div class="flex items-center gap-2 min-w-0">
                                        <div class="w-1 h-1 rounded-full bg-[#00f5d4]"></div>
                                        <div class="text-[10px] font-bold text-gray-500 truncate">
                                            <span x-text="match.stadium ? match.stadium.city : 'TBD'"></span>
                                            <template x-if="match.stadium && match.stadium.region">
                                                <span x-text="', ' + match.stadium.region"></span>
                                            </template>
                                        </div>
                                    </div>
                                    <div class="text-[10px] font-black text-white px-2 py-1 bg-white/5 rounded-lg border border-white/5 tabular-nums">
                                        <span x-text="formatDateShort(match.start_time)"></span>
                                    </div>
                                </div>
                            </a>
                        </template>
                    </div>
                </div>
            </template>
            
            <div x-show="Object.keys(groupedMatches).length === 0" class="text-center py-32 glass-card border-dashed">
                <div class="text-6xl mb-6 grayscale opacity-50">⚽</div>
                <h3 class="text-2xl font-black text-white mb-2">No results for "<span class="text-[#00f5d4]" x-text="search"></span>"</h3>
                <p class="text-gray-500 max-w-sm mx-auto">Try searching for a different city, team, or check your spelling.</p>
                <button @click="search = ''; stage = 'all'; group = 'all'" class="mt-8 px-8 py-3 bg-[#00f5d4] text-black font-black rounded-xl hover:scale-105 transition-transform">Clear search</button>
            </div>
        </section>
    </div>

    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('matchDashboard', (matches) => ({
                matches: matches,
                stage: 'all',
                group: 'all',
                search: '',
                stages: [
                    { id: 'all', label: 'All Stages' },
                    { id: 'group', label: 'Groups' },
                    { id: 'round_of_32', label: 'R32' },
                    { id: 'round_of_16', label: 'R16' },
                    { id: 'quarter_final', label: 'QF' },
                    { id: 'semi_final', label: 'SF' },
                    { id: 'final', label: 'Final' },
                ],
                groups: ['A', 'B', 'C', 'D', 'E', 'F', 'G', 'H', 'I', 'J', 'K', 'L'],

                setStage(id) {
                    this.stage = id;
                    if (id !== 'group' && id !== 'all') {
                        this.group = 'all';
                    }
                },
                setGroup(g) {
                    this.group = g;
                    this.stage = 'group';
                },
                get filteredMatches() {
                    const searchTerm = this.search.toLowerCase().trim();

                    return this.matches.filter(m => {
                        const stageMatch = this.stage === 'all' || m.stage === this.stage;
                        const groupMatch = this.group === 'all' || m.group === this.group;
                        if (this.group !== 'all' && !groupMatch) return false;
                        if (!stageMatch) return false;

                        if (!searchTerm) return true;

                        const searchableContent = [
                            m.home_team.display_name,
                            m.away_team.display_name,
                            m.stadium?.name || '',
                            m.stadium?.city || '',
                            m.stadium?.region || '',
                            m.search_date
                        ].join(' ').toLowerCase();

                        return searchableContent.includes(searchTerm);
                    });
                },
                get groupedMatches() {
                    const grouped = {};
                    this.filteredMatches.forEach(match => {
                        const localDate = new Date(match.start_time);
                        const dateKey = localDate.getFullYear() + '-' + 
                                       String(localDate.getMonth() + 1).padStart(2, '0') + '-' + 
                                       String(localDate.getDate()).padStart(2, '0');
                                       
                        if (!grouped[dateKey]) grouped[dateKey] = [];
                        grouped[dateKey].push(match);
                    });
                    
                    const sortedDates = Object.keys(grouped).sort();
                    const result = {};
                    sortedDates.forEach(date => {
                        result[date] = grouped[date];
                    });
                    return result;
                },
                formatGroupDate(dateKey) {
                    const date = new Date(dateKey + 'T12:00:00');
                    return date.toLocaleDateString('en-US', { weekday: 'short', month: 'short', day: 'numeric', year: 'numeric' });
                },
                formatDateShort(dateStr) {
                    const date = new Date(dateStr);
                    return date.toLocaleDateString('en-US', { month: 'short', day: 'numeric' });
                },
                formatTime(dateStr) {
                    const date = new Date(dateStr);
                    return date.toLocaleTimeString('en-US', { hour: '2-digit', minute: '2-digit', hour12: false });
                }
            }));
        });
    </script>
</div>
