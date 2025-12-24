<div class="min-h-[calc(100vh-100px)] flex items-center justify-center px-4 py-8">
    <div class="w-full max-w-2xl">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Join the Action! 🚀</h1>
            <p class="text-gray-400">Create your account to compete in the global leaderboard</p>
        </div>

        <!-- Register Card -->
        <div class="glass-card p-8">
            <form wire:submit="register" class="space-y-6">
                <div class="grid md:grid-cols-2 gap-6">
                    <!-- Personal Info -->
                    <div class="space-y-6">
                        <h3 class="text-lg font-semibold text-white border-b border-white/10 pb-2">Personal Details</h3>
                        
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Username</label>
                            <input 
                                wire:model="name" 
                                type="text" 
                                class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#00f5d4] transition"
                                placeholder="CaptainTsubasa"
                            >
                            @error('name') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Email Address</label>
                            <input 
                                wire:model="email" 
                                type="email" 
                                class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#00f5d4] transition"
                                placeholder="you@example.com"
                            >
                            @error('email') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Password</label>
                            <input 
                                wire:model="password" 
                                type="password" 
                                class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#00f5d4] transition"
                                placeholder="••••••••"
                            >
                            @error('password') <span class="text-sm text-red-500 mt-1">{{ $message }}</span> @enderror
                        </div>

                    <!-- Password Confirm -->
                        <div>
                            <label class="block text-sm font-medium text-gray-400 mb-1">Confirm Password</label>
                            <input 
                                wire:model="password_confirmation" 
                                type="password" 
                                class="w-full bg-[#0a0e1a]/50 border border-white/10 rounded-lg px-4 py-3 text-white focus:outline-none focus:border-[#00f5d4] transition"
                                placeholder="••••••••"
                            >
                        </div>
                    </div>

                </div>

                <!-- Terms -->
                <div class="pt-4 border-t border-white/10">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="terms" type="checkbox" class="rounded bg-[#0a0e1a]/50 border-white/10 text-[#00f5d4] focus:ring-[#00f5d4]">
                        <span class="text-sm text-gray-400">
                            I agree to the <a href="#" class="text-[#00f5d4] hover:underline">Terms of Service</a> and <a href="#" class="text-[#00f5d4] hover:underline">Privacy Policy</a>
                        </span>
                    </label>
                    @error('terms') <div class="text-sm text-red-500 mt-1">{{ $message }}</div> @enderror
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary py-3 text-lg relative group">
                    <span wire:loading.remove class="group-hover:scale-105 transition-transform inline-block">Create Account 🚀</span>
                    <span wire:loading class="absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-[#0a0e1a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>
        </div>

        <!-- Login Link -->
        <p class="text-center text-gray-400 mt-8">
            Already have an account? 
            <a href="{{ route('login') }}" class="text-[#00f5d4] hover:underline font-medium">Sign in here</a>
        </p>
    </div>
</div>
