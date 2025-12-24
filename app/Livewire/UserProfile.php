<?php

namespace App\Livewire;

use App\Models\Team;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title;
use Livewire\Component;

#[Layout('components.layouts.app')]
#[Title('My Profile')]
class UserProfile extends Component
{
    public $name = '';
    public $email = '';
    public $password = '';
    public $password_confirmation = '';
    public $selectedTeams = [];

    public function mount()
    {
        $user = Auth::user();
        $this->name = $user->name;
        $this->email = $user->email;
        $this->selectedTeams = $user->favoriteTeams()->pluck('team_id')->toArray();
    }

    public function updateProfile()
    {
        $user = Auth::user();

        $this->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'email', 'max:255', Rule::unique('users')->ignore($user->id)],
            'password' => ['nullable', 'string', 'min:8', 'confirmed'],
        ]);

        $user->name = $this->name;
        $user->email = $this->email;

        if ($this->password) {
            $user->password = Hash::make($this->password);
        }

        $user->save();

        $this->dispatch('notify', message: 'Profile updated successfully!');
    }



    public function updateFavorites()
    {
        $user = Auth::user();
        $user->favoriteTeams()->sync($this->selectedTeams);
        
        $this->dispatch('notify', message: 'Favorite teams updated!');
    }

    public function render()
    {
        $teams = Team::where('is_placeholder', false)
            ->orderBy('name')
            ->get()
            ->sortByDesc(fn ($team) => in_array($team->id, $this->selectedTeams));

        return view('livewire.user-profile', [
            'teams' => $teams
        ]);
    }
}
