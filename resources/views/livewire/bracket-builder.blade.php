<div class="min-h-[calc(100vh-100px)] pt-4 pb-12 px-4 select-none"
    x-data="{
        bracket: @entangle('bracketData'),
        isLocked: @entangle('isLocked'),
        matches: {{ Js::from($r32Matches) }},
        pairings: {{ Js::from($pairings) }},
        teams: {{ Js::from($teams->keyBy('id')) }},
        predictions: {{ Js::from($predictedTeams) }},

        init() {
            // Ensure data structure exists
            if (!this.bracket.r32) this.bracket.r32 = new Array(16).fill(null);
            if (!this.bracket.r16) this.bracket.r16 = new Array(8).fill(null);
            if (!this.bracket.qf) this.bracket.qf = new Array(4).fill(null);
            if (!this.bracket.sf) this.bracket.sf = new Array(2).fill(null);
            if (!this.bracket.final) this.bracket.final = new Array(1).fill(null);
        },
        
        getFlag(teamId) {
            if (!teamId) return '';
            // Check predictions first (resolved placeholders)
            if (this.predictions && this.predictions[teamId]) {
                return this.predictions[teamId].flag_url;
            }
            if (!this.teams[teamId]) return '';
            return this.teams[teamId].flag_url;
        },
        
        getName(teamId) {
            if (!teamId) return 'TBD';
             // Check predictions first
            if (this.predictions && this.predictions[teamId]) {
                 let team = this.predictions[teamId];
                 let name = typeof team.name === 'object' ? (team.name['{{ app()->getLocale() }}'] || team.name['en'] || Object.values(team.name)[0]) : team.name;
                 return name;
            }

            if (!this.teams[teamId]) return 'TBD';
            let team = this.teams[teamId];
            if (typeof team.name === 'object') {
                return team.name['{{ app()->getLocale() }}'] || team.name['en'] || Object.values(team.name)[0];
            }
            return team.name;
        },

        // Advance a team to the next round
        advance(round, matchIndex, teamId) {
            if (this.isLocked || !teamId) return;
            
            // Update current round winner
            this.bracket[round][matchIndex] = teamId;
        },

        // Helper to check if a team is selected as winner
        isWinner(round, matchIndex, teamId) {
            return teamId && this.bracket[round][matchIndex] == teamId;
        },

        // Get participants for R16 match based on R32 results using dynamic pairings
        getR16Home(index) { 
            let pair = this.pairings.r16[index];
            return pair ? this.bracket.r32[pair.home] : null; 
        },
        getR16Away(index) { 
            let pair = this.pairings.r16[index];
            return pair ? this.bracket.r32[pair.away] : null; 
        },

        // Get participants for QF match based on R16 results
        getQFHome(index) { return this.bracket.r16[index * 2]; },
        getQFAway(index) { return this.bracket.r16[(index * 2) + 1]; },

        // Get participants for SF match based on QF results
        getSFHome(index) { return this.bracket.qf[index * 2]; },
        getSFAway(index) { return this.bracket.qf[(index * 2) + 1]; },

        // Get participants for Final match based on SF results
        getFinalHome() { return this.bracket.sf[0]; },
        getFinalAway() { return this.bracket.sf[1]; }
    }"
>
    <!-- Header Controls -->
    <div class="max-w-7xl mx-auto mb-6 flex justify-between items-center bg-[#0a0e1a]/80 backdrop-blur sticky top-[64px] z-30 py-4 border-b border-white/10">
        <div>
            <h1 class="text-2xl font-bold text-white">Result Bracket</h1>
            <p class="text-xs text-gray-400">Values based on your current predictions. Predict the path from Round of 32 to the Final.</p>
        </div>
        <button 
            wire:click="saveBracket" 
            class="btn-primary flex items-center gap-2"
            :class="{ 'opacity-50 cursor-not-allowed': isLocked }"
            :disabled="isLocked"
        >
            <span wire:loading.remove>Save Prediction</span>
            <span wire:loading>Saving...</span>
        </button>
    </div>

    <!-- Drag & Scroll Container -->
    <div class="overflow-x-auto pb-12 custom-scrollbar">
        <div class="min-w-[1600px] flex justify-between gap-8 px-4">
            
            <!-- Round of 32 -->
            <div class="flex flex-col justify-around gap-4 w-64">
                <h3 class="text-center text-[#ff006e] font-bold uppercase tracking-widest text-sm mb-4">Round of 32</h3>
                <template x-for="(match, index) in matches" :key="match.id">
                    <div class="glass-card p-2 flex flex-col gap-1 relative group bg-[#0a0e1a]/40 border border-white/5 hover:border-[#00f5d4]/30 transition">
                        <!-- Home Team -->
                        <div @click="advance('r32', index, match.home_team_id)" 
                             class="flex items-center justify-between p-1.5 rounded cursor-pointer transition"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('r32', index, match.home_team_id), 'hover:bg-[#00f5d4]/10': !isWinner('r32', index, match.home_team_id) }">
                            <div class="flex items-center gap-2 overflow-hidden">
                                <img :src="getFlag(match.home_team_id)" class="w-4 h-4 rounded-full object-cover">
                                <span class="text-white text-xs font-bold truncate" x-text="getName(match.home_team_id)"></span>
                            </div>
                        </div>
                        <!-- Away Team -->
                        <div @click="advance('r32', index, match.away_team_id)" 
                             class="flex items-center justify-between p-1.5 rounded cursor-pointer transition"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('r32', index, match.away_team_id), 'hover:bg-[#00f5d4]/10': !isWinner('r32', index, match.away_team_id) }">
                             <div class="flex items-center gap-2 overflow-hidden">
                                <img :src="getFlag(match.away_team_id)" class="w-4 h-4 rounded-full object-cover">
                                <span class="text-white text-xs font-bold truncate" x-text="getName(match.away_team_id)"></span>
                            </div>
                        </div>
                        
                        <!-- Connector -->
                        <div class="absolute right-[-32px] top-1/2 w-8 h-[2px] bg-white/10 hidden md:block"></div>
                    </div>
                </template>
            </div>

            <!-- Round of 16 (8 Matches) -->
            <div class="flex flex-col justify-around gap-8 w-64 pt-8">
                <h3 class="text-center text-[#ff006e] font-bold uppercase tracking-widest text-sm mb-4">Round of 16</h3>
                <template x-for="(junk, index) in 8"> <!-- 8 matches -->
                    <div class="glass-card p-3 flex flex-col gap-2 relative h-full justify-center">
                        <!-- Slot 1 (From R32 Winner i*2) -->
                        <div @click="advance('r16', index, getR16Home(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('r16', index, getR16Home(index)) && getR16Home(index), 'opacity-50': !getR16Home(index), 'hover:bg-[#00f5d4]/10': getR16Home(index) }">
                             <img x-show="getR16Home(index)" :src="getFlag(getR16Home(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getR16Home(index))"></span>
                        </div>
                        
                        <!-- Slot 2 (From R32 Winner i*2 + 1) -->
                         <div @click="advance('r16', index, getR16Away(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('r16', index, getR16Away(index)) && getR16Away(index), 'opacity-50': !getR16Away(index), 'hover:bg-[#00f5d4]/10': getR16Away(index) }">
                             <img x-show="getR16Away(index)" :src="getFlag(getR16Away(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getR16Away(index))"></span>
                        </div>
                        <div class="absolute right-[-32px] top-1/2 w-8 h-[2px] bg-white/10 hidden md:block"></div>
                    </div>
                </template>
            </div>

            <!-- Quarter Finals (4 Matches) -->
             <div class="flex flex-col justify-around gap-16 w-64 pt-16">
                <h3 class="text-center text-[#ff006e] font-bold uppercase tracking-widest text-sm mb-4">Quarter Finals</h3>
                <template x-for="(junk, index) in 4">
                    <div class="glass-card p-3 flex flex-col gap-2 relative h-full justify-center">
                         <!-- Slot 1 -->
                        <div @click="advance('qf', index, getQFHome(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('qf', index, getQFHome(index)) && getQFHome(index), 'opacity-50': !getQFHome(index), 'hover:bg-[#00f5d4]/10': getQFHome(index) }">
                             <img x-show="getQFHome(index)" :src="getFlag(getQFHome(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getQFHome(index))"></span>
                        </div>
                         <!-- Slot 2 -->
                        <div @click="advance('qf', index, getQFAway(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('qf', index, getQFAway(index)) && getQFAway(index), 'opacity-50': !getQFAway(index), 'hover:bg-[#00f5d4]/10': getQFAway(index) }">
                             <img x-show="getQFAway(index)" :src="getFlag(getQFAway(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getQFAway(index))"></span>
                        </div>
                        <div class="absolute right-[-32px] top-1/2 w-8 h-[2px] bg-white/10 hidden md:block"></div>
                    </div>
                </template>
            </div>

            <!-- Semi Finals (2 Matches) -->
            <div class="flex flex-col justify-around gap-32 w-64 pt-32">
                 <h3 class="text-center text-[#ff006e] font-bold uppercase tracking-widest text-sm mb-4">Semi Finals</h3>
                <template x-for="(junk, index) in 2">
                     <div class="glass-card p-3 flex flex-col gap-2 relative h-full justify-center">
                         <!-- Slot 1 -->
                        <div @click="advance('sf', index, getSFHome(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('sf', index, getSFHome(index)) && getSFHome(index), 'opacity-50': !getSFHome(index), 'hover:bg-[#00f5d4]/10': getSFHome(index) }">
                             <img x-show="getSFHome(index)" :src="getFlag(getSFHome(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getSFHome(index))"></span>
                        </div>
                         <!-- Slot 2 -->
                        <div @click="advance('sf', index, getSFAway(index))"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px]"
                             :class="{ 'bg-[#00f5d4]/20 ring-1 ring-[#00f5d4]': isWinner('sf', index, getSFAway(index)) && getSFAway(index), 'opacity-50': !getSFAway(index), 'hover:bg-[#00f5d4]/10': getSFAway(index) }">
                             <img x-show="getSFAway(index)" :src="getFlag(getSFAway(index))" class="w-4 h-4 rounded-full">
                             <span class="text-xs font-bold truncate" x-text="getName(getSFAway(index))"></span>
                        </div>
                        <div class="absolute right-[-32px] top-1/2 w-8 h-[2px] bg-white/10 hidden md:block"></div>
                    </div>
                </template>
            </div>

            <!-- Final -->
            <div class="flex flex-col justify-center w-64 pt-48">
                 <h3 class="text-center text-[#ffc300] font-bold uppercase tracking-widest text-sm mb-4">Final</h3>
                 <div class="glass-card p-6 flex flex-col gap-4 relative h-48 justify-center border-2 border-[#ffc300]/30 shadow-[0_0_30px_rgba(255,195,0,0.1)]">
                    <div class="text-center">
                         <!-- Slot 1 (SF Winner 1) -->
                        <div @click="advance('final', 0, getFinalHome())"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px] justify-center mb-2"
                             :class="{ 'bg-[#ffc300]/20 ring-1 ring-[#ffc300]': isWinner('final', 0, getFinalHome()) && getFinalHome(), 'opacity-50': !getFinalHome(), 'hover:bg-[#ffc300]/10': getFinalHome() }">
                             <img x-show="getFinalHome()" :src="getFlag(getFinalHome())" class="w-6 h-6 rounded-full">
                             <span class="text-sm font-bold truncate" x-text="getName(getFinalHome())"></span>
                        </div>
                        
                         <div class="text-[10px] text-gray-500">VS</div>

                         <!-- Slot 2 (SF Winner 2) -->
                        <div @click="advance('final', 0, getFinalAway())"
                             class="flex items-center gap-2 p-1.5 rounded cursor-pointer transition min-h-[28px] justify-center mt-2"
                             :class="{ 'bg-[#ffc300]/20 ring-1 ring-[#ffc300]': isWinner('final', 0, getFinalAway()) && getFinalAway(), 'opacity-50': !getFinalAway(), 'hover:bg-[#ffc300]/10': getFinalAway() }">
                             <img x-show="getFinalAway()" :src="getFlag(getFinalAway())" class="w-6 h-6 rounded-full">
                             <span class="text-sm font-bold truncate" x-text="getName(getFinalAway())"></span>
                        </div>

                    </div>
                    
                    <div x-show="bracket.final[0]" class="absolute -bottom-12 left-0 right-0 text-center animate-bounce">
                        <div class="text-xs text-[#ffc300] uppercase tracking-wider mb-1">Champion</div>
                        <div class="font-bold text-xl text-white" x-text="getName(bracket.final[0])"></div>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>

