<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="text-center mb-10">
        <h1 class="text-4xl font-black text-white italic uppercase tracking-wide">
            Global <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00f5d4] to-[#00ae98]">Leaderboard</span>
        </h1>
        <p class="text-gray-400 mt-2">See who's leading the World Cup predictions!</p>
    </div>

    <div class="glass-card overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full text-left">
                <thead>
                    <tr class="bg-white/5 border-b border-white/10 text-gray-400 text-xs uppercase tracking-wider">
                        <th class="p-4 text-center w-16">Rank</th>
                        <th class="p-4">User</th>
                        <th class="p-4 text-center">Points</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-white/5">
                    @foreach($users as $user)
                        @php
                            $rank = ($users->currentPage() - 1) * $users->perPage() + $loop->iteration;
                            $isTop3 = $rank <= 3;
                            $rankColor = match($rank) {
                                1 => 'text-yellow-400',
                                2 => 'text-gray-300',
                                3 => 'text-amber-600',
                                default => 'text-gray-500'
                            };
                        @endphp
                        <tr class="hover:bg-white/5 transition group">
                            <td class="p-4 text-center">
                                <span class="font-black text-xl {{ $rankColor }}">
                                    #{{ $rank }}
                                </span>
                            </td>
                            <td class="p-4">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-white/10 overflow-hidden ring-2 {{ $isTop3 ? 'ring-[#00f5d4]' : 'ring-transparent' }}">
                                        @if($user->avatar_url)
                                            <img src="{{ $user->avatar_url }}" class="w-full h-full object-cover">
                                        @else
                                            <div class="w-full h-full flex items-center justify-center text-white font-bold bg-gradient-to-br from-purple-600 to-blue-600">
                                                {{ substr($user->name, 0, 1) }}
                                            </div>
                                        @endif
                                    </div>
                                    <div>
                                        <div class="font-bold text-white group-hover:text-[#00f5d4] transition">
                                            {{ $user->name }}
                                        </div>
                                        @if($user->is_admin)
                                            <div class="text-[10px] text-[#00f5d4] uppercase font-bold tracking-wider">
                                                Admin
                                            </div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td class="p-4 text-center">
                                <span class="font-black text-2xl text-white">
                                    {{ number_format($user->xp_points) }}
                                </span>
                                <span class="text-xs text-gray-500 block">PTS</span>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        
        <div class="p-4 border-t border-white/10">
            {{ $users->links() }}
        </div>
    </div>
</div>
