<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="mb-8">
        <a href="{{ route('leagues.index') }}" class="text-gray-500 hover:text-white text-sm flex items-center gap-2 mb-4">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Back to Leagues
        </a>
        
        <div class="glass-card p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 p-6 opacity-20">
                <svg class="w-32 h-32 text-white" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
            </div>
            
            <div class="relative z-10">
                <h1 class="text-3xl font-black text-white italic uppercase tracking-wide mb-2">
                    {{ $league->name }}
                </h1>
                <p class="text-gray-400 max-w-2xl">{{ $league->description }}</p>
                
                <div class="mt-6 flex items-center gap-4">
                    <div class="bg-black/40 px-4 py-2 rounded-lg border border-white/10">
                        <span class="text-xs text-gray-500 uppercase block mb-0.5">Invite Code</span>
                        <span class="text-[#00f5d4] font-mono font-bold text-lg select-all">{{ $league->code }}</span>
                    </div>
                    <div class="bg-black/40 px-4 py-2 rounded-lg border border-white/10">
                        <span class="text-xs text-gray-500 uppercase block mb-0.5">Members</span>
                        <span class="text-white font-bold text-lg">{{ $league->members->count() }}</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Leaderboard Table -->
    <div class="glass-card overflow-hidden">
        <div class="px-6 py-4 border-b border-white/10 bg-white/5 flex justify-between items-center">
            <h3 class="text-lg font-bold text-white">Standings</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-gray-400 text-[10px] uppercase tracking-[0.2em]">
                        <th class="p-4 text-center w-16">Rank</th>
                        <th class="p-4">Member</th>
                        <th class="p-4 text-center">Correct Scores</th>
                        <th class="p-4 text-right">Total Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($members as $member)
                        @php
                            $rank = $loop->iteration;
                            $isTop3 = $rank <= 3;
                            $rankColor = match($rank) {
                                1 => 'text-yellow-400',
                                2 => 'text-gray-300',
                                3 => 'text-amber-600',
                                default => 'text-gray-500'
                            };
                            $isMe = $member->id === auth()->id();
                        @endphp
                        <tr class="hover:bg-white/5 transition group {{ $isMe ? 'bg-[#3a86ff]/5' : '' }}">
                            <td class="p-4 text-center">
                                <span class="font-black text-xl {{ $rankColor }}">
                                    #{{ $rank }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <div class="relative">
                                        <div class="w-10 h-10 rounded-full bg-white/10 overflow-hidden ring-2 {{ $isTop3 ? 'ring-[#00f5d4]' : 'ring-white/10' }}">
                                            @if($member->avatar_url)
                                                <img src="{{ $member->avatar_url }}" class="w-full h-full object-cover">
                                            @else
                                                <div class="w-full h-full flex items-center justify-center text-white font-bold text-sm bg-gradient-to-br from-indigo-600 to-violet-600">
                                                    {{ substr($member->name, 0, 1) }}
                                                </div>
                                            @endif
                                        </div>
                                        @if($rank === 1)
                                            <div class="absolute -top-2 -right-2 text-lg">👑</div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-white flex items-center gap-2 group-hover:text-[#00f5d4] transition {{ $isMe ? 'text-[#00f5d4]' : '' }}">
                                            {{ $member->name }}
                                            @if($isMe)
                                                <span class="text-[9px] bg-[#3a86ff] px-1.5 py-0.5 rounded text-white uppercase font-black">You</span>
                                            @endif
                                            @if($league->owner_id === $member->id)
                                                <span class="text-[9px] bg-white/10 px-1.5 py-0.5 rounded text-gray-400 uppercase font-black">Admin</span>
                                            @endif
                                        </div>
                                        <div class="text-[10px] text-gray-500 uppercase tracking-widest mt-0.5">Joined {{ \Carbon\Carbon::parse($member->pivot->joined_at)->format('M Y') }}</div>
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <div class="flex flex-col items-center">
                                    <span class="text-white font-bold">{{ $member->correct_scores_count }}</span>
                                    <span class="text-[9px] text-gray-500 uppercase tracking-tighter">Exact hits</span>
                                </div>
                            </td>
                            <td class="p-4 text-right">
                                <span class="font-black text-2xl text-white">
                                    {{ number_format($member->pivot->total_score) }}
                                </span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
