<div class="p-6">
    <div class="flex justify-between items-center mb-6">
        <h2 class="text-2xl font-bold text-white">Match Manager</h2>
        
        <!-- Search -->
        <input 
            wire:model.live.debounce.300ms="search" 
            type="text" 
            placeholder="Search teams..." 
            class="bg-white/5 border border-white/10 rounded-lg px-4 py-2 text-white placeholder-gray-500 focus:outline-none focus:border-[#00f5d4] transition"
        >
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/50 text-green-400">
            {{ session('success') }}
        </div>
    @endif

    <div class="overflow-x-auto rounded-lg border border-white/10">
        <table class="w-full text-left bg-white/5">
            <thead>
                <tr class="bg-white/10 text-gray-400 text-sm uppercase">
                    <th class="p-4">Date</th>
                    <th class="p-4">Stage</th>
                    <th class="p-4 text-right">Home</th>
                    <th class="p-4 text-center">Score</th>
                    <th class="p-4">Away</th>
                    <th class="p-4 text-center">Actions</th>
                </tr>
            </thead>
            <tbody class="divide-y divide-white/5">
                @foreach($matches as $match)
                    <tr wire:key="{{ $match->id }}" class="hover:bg-white/5 transition">
                        <td class="p-4 text-gray-300 text-sm">
                            {{ \Carbon\Carbon::parse($match->start_time)->format('d M H:i') }}
                        </td>
                        <td class="p-4 text-gray-400 text-xs text-center">
                            <span class="px-2 py-1 rounded bg-white/10 whitespace-nowrap">
                                {{ $match->stage_name }}
                            </span>
                        </td>

                        <!-- Home Team -->
                        <td class="p-4 text-right">
                            <div class="flex items-center justify-end gap-3">
                                <span class="font-bold text-white">{{ $match->homeTeam->getTranslation('name', 'en') }}</span>
                                @if($match->homeTeam->flag_url)
                                    <img src="{{ $match->homeTeam->flag_url }}" class="w-6 h-4 object-cover rounded">
                                @else
                                    <span class="text-xs text-gray-500">{{ $match->homeTeam->iso_code }}</span>
                                @endif
                            </div>
                        </td>

                        <!-- Score Input / Display -->
                        <td class="p-4 text-center">
                            @if($editingGameId == $match->id)
                                <div class="flex items-center justify-center gap-2">
                                    <input type="number" wire:model="homeScore" class="w-12 text-center bg-black/50 border border-white/20 rounded py-1 text-white">
                                    <span class="text-gray-500">-</span>
                                    <input type="number" wire:model="awayScore" class="w-12 text-center bg-black/50 border border-white/20 rounded py-1 text-white">
                                </div>
                                <!-- Penalties if needed (simplified UI for now, maybe toggle?) -->
                                @if($match->stage !== 'group')
                                    <div class="flex items-center justify-center gap-2 mt-1 text-xs">
                                        <input type="number" wire:model="homePens" placeholder="P" class="w-8 text-center bg-black/50 border border-white/20 rounded py-0.5 text-gray-300">
                                        <span class="text-gray-600">:</span>
                                        <input type="number" wire:model="awayPens" placeholder="P" class="w-8 text-center bg-black/50 border border-white/20 rounded py-0.5 text-gray-300">
                                    </div>
                                @endif
                            @else
                                <div class="text-white font-bold text-lg">
                                    {{ $match->home_score ?? '-' }} - {{ $match->away_score ?? '-' }}
                                </div>
                                @if($match->home_score_penalties)
                                    <div class="text-xs text-gray-500">
                                        ({{ $match->home_score_penalties }} - {{ $match->away_score_penalties }})
                                    </div>
                                @endif
                            @endif
                        </td>

                        <!-- Away Team -->
                        <td class="p-4">
                            <div class="flex items-center gap-3">
                                @if($match->awayTeam->flag_url)
                                    <img src="{{ $match->awayTeam->flag_url }}" class="w-6 h-4 object-cover rounded">
                                @else
                                    <span class="text-xs text-gray-500">{{ $match->awayTeam->iso_code }}</span>
                                @endif
                                <span class="font-bold text-white">{{ $match->awayTeam->getTranslation('name', 'en') }}</span>
                            </div>
                        </td>

                        <!-- Actions -->
                        <td class="p-4 text-center">
                            @if($editingGameId == $match->id)
                                <div class="flex items-center justify-center gap-2">
                                    <button wire:click="save({{ $match->id }})" class="p-2 bg-green-500/20 text-green-400 hover:bg-green-500/30 rounded transition" title="Save">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                    </button>
                                    <button wire:click="cancel" class="p-2 bg-red-500/20 text-red-400 hover:bg-red-500/30 rounded transition" title="Cancel">
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                            @else
                                <button wire:click="edit({{ $match->id }})" class="p-2 bg-white/5 text-gray-400 hover:text-white hover:bg-white/10 rounded transition">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z"></path></svg>
                                </button>
                            @endif
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

    <div class="mt-6">
        {{ $matches->links() }}
    </div>
</div>
