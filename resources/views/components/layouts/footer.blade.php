<footer class="border-t border-white/10 bg-[#0a0e1a]/80 backdrop-blur-md mt-auto">
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Brand -->
            <div class="space-y-4">
                <div class="flex items-center gap-2">
                    <span class="text-2xl font-bold bg-gradient-to-r from-[#00f5d4] to-[#3a86ff] bg-clip-text text-transparent">
                        WC26
                    </span>
                </div>
                <p class="text-sm text-gray-400">
                    The ultimate companion for the 2026 World Cup. Predict matches, compete with friends, and track your bracket.
                </p>
            </div>

            <!-- Quick Links -->
            <div>
                <h3 class="font-bold text-white mb-4">Quick Links</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="{{ route('home') }}" class="hover:text-[#00f5d4] transition">Matches</a></li>
                    <li><a href="{{ route('brackets') }}" class="hover:text-[#00f5d4] transition">Bracket Builder</a></li>
                    <li><a href="{{ route('leagues.index') }}" class="hover:text-[#00f5d4] transition">Leagues</a></li>
                    <li><a href="{{ route('profile') }}" class="hover:text-[#00f5d4] transition">My Profile</a></li>
                </ul>
            </div>

            <!-- Legal / Info -->
            <div>
                <h3 class="font-bold text-white mb-4">Information</h3>
                <ul class="space-y-2 text-sm text-gray-400">
                    <li><a href="#" class="hover:text-[#00f5d4] transition">Rules & Scoring</a></li>
                    <li><a href="#" class="hover:text-[#00f5d4] transition">Privacy Policy</a></li>
                    <li><a href="#" class="hover:text-[#00f5d4] transition">Terms of Service</a></li>
                </ul>
            </div>
        </div>

        <div class="mt-8 pt-8 border-t border-white/5 text-center text-xs text-gray-500">
            <p>&copy; {{ date('Y') }} WC26 Predictor. All rights reserved.</p>
            <p class="mt-1">Not affiliated with FIFA.</p>
        </div>
    </div>
</footer>
