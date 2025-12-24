<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use Livewire\WithPagination;

class Leaderboard extends Component
{
    use WithPagination;

    public function render()
    {
        // Fetch top 50 users based on total points
        $users = User::orderByDesc('xp_points')
            ->orderBy('id') // Tie-breaker
            ->paginate(50);

        return view('livewire.leaderboard', [
            'users' => $users
        ]);
    }
}
