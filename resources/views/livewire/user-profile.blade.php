<div class="min-h-[calc(100vh-100px)] py-8 px-4">
    <div class="max-w-6xl mx-auto space-y-8">
        <!-- Header -->
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-white mb-2">My Profile 👤</h1>
                <p class="text-gray-400">Manage your account and favorite teams</p>
            </div>
            
            <div class="flex items-center gap-4">
                <div class="text-right">
                    <div class="text-white font-bold">{{ auth()->user()->name }}</div>
                    <div class="text-[#00f5d4] text-sm">{{ auth()->user()->xp_points }} XP</div>
                </div>
                <img src="{{ auth()->user()->avatar_url }}" alt="Avatar" class="w-12 h-12 rounded-full border-2 border-[#00f5d4]">
            </div>
        </div>

        <div class="grid lg:grid-cols-3 gap-8">
            <!-- Left Column: Profile Settings -->
            <div class="lg:col-span-1">
                <div class="glass-card p-6 sticky top-24">
                    <h3 class="text-xl font-bold text-white mb-6 flex items-center gap-2">
                        <svg class="w-5 h-5 text-[#00f5d4]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path></svg>
                        Account Details
                    </h3>

                    <form wire:submit="updateProfile" class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                            <input wire:model="name" type="text" class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]">
                            @error('name') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email</label>
                            <input wire:model="email" type="email" class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]">
                            @error('email') <span class="text-xs text-red-500">{{ $message }}</span> @enderror
                        </div>

                        <div class="pt-4 border-t border-white/10">
                            <label class="block text-sm font-medium text-gray-400 mb-1">New Password (Optional)</label>
                            <input wire:model="password" type="password" class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]" placeholder="Leave empty to keep current">
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Confirm New Password</label>
                            <input wire:model="password_confirmation" type="password" class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-2 text-white focus:outline-none focus:border-[#00f5d4]">
                        </div>

                        <button type="submit" class="w-full btn-primary py-2 mt-4 relative group">
                            <span wire:loading.remove wire:target="updateProfile">Save Changes</span>
                            <span wire:loading wire:target="updateProfile">Saving...</span>
                        </button>
                    </form>

                    <div class="mt-6 pt-6 border-t border-white/10">
                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <button type="submit" class="w-full text-red-500 hover:text-red-400 text-sm font-medium transition flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path></svg>
                                Sign Out
                            </button>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Right Column: Favorite Teams -->
            <div class="lg:col-span-2">
                <div class="glass-card p-6" x-data="{ selectedTeams: @entangle('selectedTeams') }">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-xl font-bold text-white flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#f15bb5]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path></svg>
                            Favorite Teams
                        </h3>
                        <button wire:click="updateFavorites" class="btn-primary py-2 px-6 text-sm">
                            <span wire:loading.remove wire:target="updateFavorites">Update Favorites</span>
                            <span wire:loading wire:target="updateFavorites">Saving...</span>
                        </button>
                    </div>

                    <p class="text-gray-400 text-sm mb-6">Select the teams you support. This will customize your experience and show match notifications.</p>

                    <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-4">
                        @foreach($teams as $team)
                            <div 
                                @click="selectedTeams.includes({{ $team->id }}) 
                                    ? selectedTeams = selectedTeams.filter(id => id !== {{ $team->id }}) 
                                    : selectedTeams.push({{ $team->id }})"
                                class="cursor-pointer relative group rounded-xl bg-[#0a0e1a]/30 border border-white/5 overflow-hidden transition-all duration-300 hover:border-white/20"
                                :class="{ 'ring-2 ring-[#00f5d4] bg-[#00f5d4]/10': selectedTeams.includes({{ $team->id }}) }"
                            >
                                <div class="p-4 flex flex-col items-center gap-3 text-center">
                                    <div class="w-12 h-12 rounded-full bg-white/5 p-2 flex items-center justify-center group-hover:scale-110 transition-transform">
                                        <img src="{{ $team->flag_url }}" alt="{{ $team->display_name }}" class="w-8 h-auto shadow-sm">
                                    </div>
                                    <span class="text-sm font-medium transition-colors"
                                        :class="selectedTeams.includes({{ $team->id }}) ? 'text-white' : 'text-gray-400 group-hover:text-gray-200'"
                                    >
                                        {{ $team->display_name }}
                                    </span>
                                </div>
                                
                                <div x-show="selectedTeams.includes({{ $team->id }})" class="absolute top-2 right-2 text-[#00f5d4]">
                                    <svg class="w-5 h-5 fill-current" viewBox="0 0 20 20"><path d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/></svg>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Notifications -->
    <div
        x-data="{ show: false, message: '' }"
        x-on:notify.window="show = true; message = $event.detail.message; setTimeout(() => show = false, 3000)"
        x-show="show"
        x-transition:enter="transition ease-out duration-300"
        x-transition:enter-start="opacity-0 transform translate-y-2"
        x-transition:enter-end="opacity-100 transform translate-y-0"
        x-transition:leave="transition ease-in duration-200"
        x-transition:leave-start="opacity-100 transform translate-y-0"
        x-transition:leave-end="opacity-0 transform translate-y-2"
        class="fixed bottom-6 right-6 bg-[#00f5d4] text-[#0a0e1a] px-6 py-3 rounded-lg shadow-xl font-bold flex items-center gap-3 z-50"
        style="display: none;"
    >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
        <span x-text="message"></span>
    </div>
</div>
