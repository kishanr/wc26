<div class="max-w-4xl mx-auto py-8 px-4">
    <div class="flex flex-col md:flex-row justify-between items-center mb-10 gap-4">
        <div>
            <h1 class="text-3xl font-black text-white italic uppercase tracking-wide">
                Private <span class="text-transparent bg-clip-text bg-gradient-to-r from-[#00f5d4] to-[#00ae98]">Leagues</span>
            </h1>
            <p class="text-gray-400 mt-1">Compete with friends and family.</p>
        </div>
        <div class="flex gap-3">
            <button wire:click="$set('showJoinModal', true)" class="px-6 py-2 rounded-lg border border-[#00f5d4] text-[#00f5d4] font-bold hover:bg-[#00f5d4]/10 transition uppercase tracking-wider text-sm">
                Join League
            </button>
            <button wire:click="$set('showCreateModal', true)" class="px-6 py-2 rounded-lg bg-gradient-to-r from-[#00f5d4] to-[#00ae98] text-white font-bold hover:shadow-lg hover:shadow-[#00f5d4]/30 transition uppercase tracking-wider text-sm transform hover:-translate-y-0.5">
                Create League
            </button>
        </div>
    </div>

    @if (session()->has('success'))
        <div class="mb-6 p-4 rounded-lg bg-green-500/20 border border-green-500/50 text-green-400 font-bold">
            {{ session('success') }}
        </div>
    @endif

    <div class="grid gap-6 md:grid-cols-2">
        @forelse($myLeagues->merge($ownedLeagues)->unique('id') as $league)
            <a href="{{ route('leagues.show', $league) }}" class="glass-card p-6 group hover:border-[#00f5d4]/50 transition relative overflow-hidden">
                <div class="absolute top-0 right-0 p-4 opacity-50 group-hover:opacity-100 transition">
                    <svg class="w-24 h-24 text-white/5 transform translate-x-8 -translate-y-8" fill="currentColor" viewBox="0 0 20 20"><path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3zM6 8a2 2 0 11-4 0 2 2 0 014 0zM16 18v-3a5.972 5.972 0 00-.75-2.906A3.005 3.005 0 0119 15v3h-3zM4.75 12.094A5.973 5.973 0 004 15v3H1v-3a3 3 0 013.75-2.906z"></path></svg>
                </div>
                
                <div class="relative z-10">
                    <h3 class="text-xl font-bold text-white mb-2 group-hover:text-[#00f5d4] transition">{{ $league->name }}</h3>
                    <p class="text-gray-400 text-sm mb-4 line-clamp-2">{{ $league->description ?? 'No description.' }}</p>
                    
                    <div class="flex items-center justify-between text-xs text-gray-500 font-mono">
                        <span class="bg-white/10 px-2 py-1 rounded">Code: <span class="text-white">{{ $league->code }}</span></span>
                        <span>{{ $league->members_count }} Members</span>
                    </div>
                </div>
            </a>
        @empty
            <div class="col-span-full glass-card p-12 text-center">
                <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-white/5 mb-4">
                    <svg class="w-8 h-8 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                </div>
                <h3 class="text-lg font-medium text-white mb-2">No Leagues Yet</h3>
                <p class="text-gray-400 mb-6 max-w-sm mx-auto">Create your own league to challenge friends or join an existing one with a code.</p>
                <div class="flex justify-center gap-4">
                    <button wire:click="$set('showCreateModal', true)" class="text-[#00f5d4] hover:text-[#00ae98] font-bold text-sm">Create One</button>
                    <span class="text-gray-600">|</span>
                    <button wire:click="$set('showJoinModal', true)" class="text-[#00f5d4] hover:text-[#00ae98] font-bold text-sm">Join One</button>
                </div>
            </div>
        @endforelse
    </div>

    <!-- Modals (Simple overlays for now) -->
    @if($showCreateModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" wire:click="$set('showCreateModal', false)"></div>
            <div class="glass-card p-6 w-full max-w-md relative z-10">
                <h2 class="text-2xl font-bold text-white mb-6">Create League</h2>
                
                <form wire:submit="createLeague" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">League Name</label>
                        <input wire:model="name" type="text" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]" placeholder="e.g. Office Pool">
                        @error('name') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Description (Optional)</label>
                        <textarea wire:model="description" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]" rows="3"></textarea>
                        @error('description') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showCreateModal', false)" class="px-4 py-2 text-gray-400 hover:text-white">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00f5d4] text-black font-bold rounded-lg hover:bg-[#00c4aa]">Create</button>
                    </div>
                </form>
            </div>
        </div>
    @endif

    @if($showJoinModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center px-4">
            <div class="absolute inset-0 bg-black/80 backdrop-blur-sm" wire:click="$set('showJoinModal', false)"></div>
            <div class="glass-card p-6 w-full max-w-md relative z-10">
                <h2 class="text-2xl font-bold text-white mb-6">Join League</h2>
                
                <form wire:submit="joinLeague" class="space-y-4">
                    <div>
                        <label class="block text-xs font-bold text-gray-400 uppercase mb-1">Enter Code</label>
                        <input wire:model="code" type="text" class="w-full bg-black/50 border border-white/10 rounded-lg px-4 py-2 text-white text-center text-2xl tracking-widest uppercase focus:outline-none focus:border-[#00f5d4]" placeholder="CODE">
                        @error('code') <span class="text-red-400 text-xs">{{ $message }}</span> @enderror
                    </div>
                    <div class="flex justify-end gap-3 mt-6">
                        <button type="button" wire:click="$set('showJoinModal', false)" class="px-4 py-2 text-gray-400 hover:text-white">Cancel</button>
                        <button type="submit" class="px-6 py-2 bg-[#00f5d4] text-black font-bold rounded-lg hover:bg-[#00c4aa]">Join</button>
                    </div>
                </form>
            </div>
        </div>
    @endif
</div>
