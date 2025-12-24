<?php

namespace App\Livewire;

use App\Models\Game;
use App\Models\Prediction;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Livewire\Attributes\Layout;
use Livewire\Attributes\Title; // Add Title attribute
use Livewire\Component;

#[Layout('components.layouts.app')]
class MatchDetail extends Component
{
    public Game $game;
    public $homeScore = 0;
    public $awayScore = 0;
    // AI and Community data (Optional - keeping for UI if wanted, but removing from slider logic)
    public $aiConfidence = 65;
    public $communitySentiment = 40;
    public ?Prediction $userPrediction = null;
    public bool $isLocked = false;
    public bool $canPredict = true;
    public string $message = '';
    public $relatedMatches = [];


    public function mount(Game $game)
    {
        $this->game = $game;
        $this->game->load(['homeTeam', 'awayTeam', 'stadium']);

        // Load Related Matches
        $query = Game::where('id', '!=', $this->game->id)
            ->with(['homeTeam', 'awayTeam', 'stadium']);
            
        if ($this->game->group) {
            $query->where('group', $this->game->group);
        } else {
            $query->where('stage', $this->game->stage);
        }
        
        $this->relatedMatches = $query->orderBy('start_time')->take(3)->get();

        $user = Auth::user();
        if ($user) {
            $prediction = Prediction::where('user_id', $user->id)
                ->where('match_id', $this->game->id)
                ->first();
            
            if ($prediction) {
                $this->homeScore = $prediction->home_score;
                $this->awayScore = $prediction->away_score;
            } else {
                $this->homeScore = 0;
                $this->awayScore = 0;
            }
        }

        $this->checkDeadline();
    }


    public function savePrediction()
    {
        if (!Auth::check()) {
            return redirect()->route('login');
        }

        if (!$this->checkDeadline()) {
            $this->message = 'Predictions are locked! 🔒';
            return;
        }

        Prediction::updateOrCreate(
            [
                'user_id' => Auth::id(),
                'match_id' => $this->game->id,
            ],
            [
                'home_score' => $this->homeScore,
                'away_score' => $this->awayScore,
                'processed' => false,
            ]
        );

        $this->message = 'Prediction saved! 🎯';
        $this->message = 'Prediction saved! 🎯';
        $this->dispatch('prediction-saved'); 
        
        $this->syncToBracket();
    }

    protected function syncToBracket()
    {
        // 1. Check if game is a knockout game
        $stageMapping = [
            'round_of_32' => 'r32',
            'round_of_16' => 'r16',
            'quarter_final' => 'qf',
            'semi_final' => 'sf',
            'final' => 'final',
        ];

        if (!array_key_exists($this->game->stage, $stageMapping)) {
            return;
        }

        $bracketStage = $stageMapping[$this->game->stage];

        // 2. Determine winner based on predicted score
        if ($this->homeScore > $this->awayScore) {
            $winnerId = $this->game->home_team_id;
        } elseif ($this->awayScore > $this->homeScore) {
            $winnerId = $this->game->away_team_id;
        } else {
            // Draw? In knockout, usually penalties decide. 
            // For simple prediction, maybe we force a winner or ignore?
            // If user predicts draw, we can't really advance anyone unless we ask for penalty winner.
            // For now, let's ignore draws or default to home? 
            // Better: If draw, do nothing / don't update bracket slot yet.
            return;
        }

        // 3. Find match index in this stage
        // Matches are strictly ordered by ID in our seeding/structure
        // We need to match the indexing used in BracketBuilder (orderBy id)
        $matchIndex = Game::where('stage', $this->game->stage)
            ->orderBy('id')
            ->pluck('id')
            ->search($this->game->id);

        if ($matchIndex === false) {
            return;
        }

        // 4. Update User's Bracket
        $user = Auth::user();
        $bracket = $user->bracket()->firstOrCreate([
            'user_id' => $user->id
        ], [
            'data' => [
                'r32' => array_fill(0, 16, null),
                'r16' => array_fill(0, 8, null),
                'qf' => array_fill(0, 4, null),
                'sf' => array_fill(0, 2, null),
                'final' => array_fill(0, 1, null),
                'champion' => null,
            ]
        ]);

        $data = $bracket->data;
        
        // Ensure structure exists (migration/safety)
        if (!isset($data[$bracketStage])) {
            return; 
        }

        // Set the winner in the slot
        $data[$bracketStage][$matchIndex] = $winnerId;

        // Save
        $bracket->data = $data;
        $bracket->save();
        
        // Optional: Dispatch event if needed, but not strictly necessary for this page
    }

    public function checkDeadline(): bool
    {
        // Prediction closes at match start time
        $deadline = Carbon::parse($this->game->start_time);
        if (now()->greaterThanOrEqualTo($deadline)) {
            $this->isLocked = true;
            $this->canPredict = false;
            return false;
        }
        $this->isLocked = false;
        $this->canPredict = true;
        return true;
    }
    
    public function getStageName(): string
    {
        return match ($this->game->stage) {
            'group' => 'Group ' . $this->game->group,
            'round_of_32' => 'Round of 32',
            'round_of_16' => 'Round of 16',
            'quarter_final' => 'Quarter Final',
            'semi_final' => 'Semi Final',
            'third_place' => '3rd Place',
            'final' => '🏆 Final',
            default => ucfirst($this->game->stage),
        };
    }

    #[Title('Match Details')] 
    public function render()
    {
        // Dynamic title can be set here if needed, or via attribute
        return view('livewire.match-detail')
            ->title($this->game->homeTeam->getTranslation('name', 'en') . ' vs ' . $this->game->awayTeam->getTranslation('name', 'en'));
    }
}
