<div class="min-h-[calc(100vh-100px)] flex items-center justify-center px-4">
    <div class="w-full max-w-md">
        <!-- Header -->
        <div class="text-center mb-8">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome Back! 👋</h1>
            <p class="text-gray-400">Sign in to make predictions and track your score</p>
        </div>

        <!-- Login Card -->
        <div class="glass-card p-8">
            <form wire:submit="login" class="space-y-6">
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

                <!-- Remember Me -->
                <div class="flex items-center justify-between">
                    <label class="flex items-center gap-2 cursor-pointer">
                        <input wire:model="remember" type="checkbox" class="rounded bg-[#0a0e1a]/50 border-white/10 text-[#00f5d4] focus:ring-[#00f5d4]">
                        <span class="text-sm text-gray-400">Remember me</span>
                    </label>
                    <a href="#" class="text-sm text-[#00f5d4] hover:underline">Forgot password?</a>
                </div>

                <!-- Submit Button -->
                <button type="submit" class="w-full btn-primary py-3 text-lg relative">
                    <span wire:loading.class="invisible">Sign In</span>
                    <span wire:loading.class.remove="hidden" class="hidden absolute inset-0 flex items-center justify-center">
                        <svg class="animate-spin h-5 w-5 text-[#0a0e1a]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </span>
                </button>
            </form>

            <!-- Divider -->
            <div class="relative my-8">
                <div class="absolute inset-0 flex items-center">
                    <div class="w-full border-t border-white/10"></div>
                </div>
                <div class="relative flex justify-center text-sm">
                    <span class="px-2 bg-[#111827] text-gray-500">Or continue with</span>
                </div>
            </div>

            <!-- Social Login (Placeholder) -->
            <div class="grid grid-cols-2 gap-4">
                <button class="flex items-center justify-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 transition text-gray-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M12.48 10.92v3.28h7.84c-.24 1.84-.853 3.187-1.787 4.133-1.147 1.147-2.933 2.4-6.053 2.4-4.827 0-8.6-3.893-8.6-8.72s3.773-8.72 8.6-8.72c2.6 0 4.507 1.027 5.907 2.347l2.307-2.307C18.747 1.44 16.133 0 12.48 0 5.867 0 .307 5.387.307 12s5.56 12 12.173 12c3.573 0 6.267-1.173 8.373-3.36 2.16-2.16 2.84-5.213 2.84-7.667 0-.76-.053-1.467-.173-2.053H12.48z"/></svg>
                    Google
                </button>
                <button class="flex items-center justify-center gap-2 py-2.5 border border-white/10 rounded-lg hover:bg-white/5 transition text-gray-300">
                    <svg class="w-5 h-5" viewBox="0 0 24 24" fill="currentColor"><path d="M16.318 13.714v5.484h9.078c-0.37 2.354-2.745 6.901-9.078 6.901-5.458 0-9.917-4.521-9.917-10.099s4.458-10.099 9.917-10.099c3.109 0 5.193 1.318 6.38 2.464l4.339-4.182c-2.786-2.599-6.396-4.182-10.719-4.182-8.844 0-16 7.151-16 16s7.156 16 16 16c9.234 0 15.365-6.49 15.365-15.635 0-1.052-0.115-1.854-0.255-2.651z"/></svg>
                    Meta
                </button>
            </div>
        </div>

        <!-- Sign Up Link -->
        <p class="text-center text-gray-400 mt-8">
            Don't have an account? 
            <a href="{{ route('register') }}" class="text-[#00f5d4] hover:underline font-medium">Create an account</a>
        </p>
    </div>
</div>
