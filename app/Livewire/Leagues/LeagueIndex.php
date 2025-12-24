<?php

namespace App\Livewire\Leagues;

use App\Models\League;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;

class LeagueIndex extends Component
{
    public $showCreateModal = false;
    public $showJoinModal = false;
    
    // Create Form
    public $name;
    public $description;
    
    // Join Form
    public $code;

    public function render()
    {
        $user = Auth::user();

        return view('livewire.leagues.league-index', [
            'myLeagues' => $user->leagues,
            'ownedLeagues' => $user->ownedLeagues
        ]);
    }

    public function createLeague()
    {
        $this->validate([
            'name' => 'required|string|min:3|max:50',
            'description' => 'nullable|string|max:200'
        ]);

        $league = League::create([
            'name' => $this->name,
            'description' => $this->description,
            'owner_id' => Auth::id()
        ]);

        // Auto-join owner
        $league->members()->attach(Auth::id(), ['total_score' => Auth::user()->xp_points]); // Start with current XP? Or 0? Let's say current global XP for now, or 0 for fresh start. Usually pools use tournament points. Let's align with Global XP for simplicity unless requested otherwise.
        
        $this->reset(['name', 'description', 'showCreateModal']);
        session()->flash('success', "League '{$league->name}' created! Code: {$league->code}");
    }

    public function joinLeague()
    {
        $this->validate([
            'code' => 'required|string|size:8|exists:leagues,code'
        ]);

        $league = League::where('code', $this->code)->first();

        if ($league->members()->where('user_id', Auth::id())->exists()) {
            $this->addError('code', 'You are already in this league.');
            return;
        }

        $league->members()->attach(Auth::id(), ['total_score' => Auth::user()->xp_points]);
        
        $this->reset(['code', 'showJoinModal']);
        session()->flash('success', "Joined league '{$league->name}' successfully!");
    }
}
