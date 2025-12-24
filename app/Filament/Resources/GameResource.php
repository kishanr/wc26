<?php

namespace App\Filament\Resources;

use App\Filament\Resources\GameResource\Pages;
use App\Models\Game;
use App\Models\Stadium;
use App\Models\Team;
use BackedEnum;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class GameResource extends Resource
{
    protected static ?string $model = Game::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-play-circle';
    }

    public static function getNavigationSort(): ?int
    {
        return 2;
    }

    public static function getModelLabel(): string
    {
        return 'Match';
    }

    public static function getPluralModelLabel(): string
    {
        return 'Matches';
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Tournament';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Match Details')
                    ->schema([
                        Select::make('home_team_id')
                            ->label('Home Team')
                            ->options(Team::where('is_placeholder', false)->get()->mapWithKeys(fn ($team) => [$team->id => $team->display_name]))
                            ->searchable()
                            ->required(),
                        Select::make('away_team_id')
                            ->label('Away Team')
                            ->options(Team::where('is_placeholder', false)->get()->mapWithKeys(fn ($team) => [$team->id => $team->display_name]))
                            ->searchable()
                            ->required()
                            ->different('home_team_id'),
                        Select::make('stadium_id')
                            ->label('Stadium')
                            ->options(Stadium::all()->pluck('name', 'id'))
                            ->searchable(),
                        DateTimePicker::make('start_time')
                            ->required()
                            ->native(false),
                    ])->columns(2),

                Section::make('Tournament Info')
                    ->schema([
                        Select::make('status')
                            ->options([
                                'scheduled' => '📅 Scheduled',
                                'live' => '🔴 Live',
                                'finished' => '✅ Finished',
                                'postponed' => '⏸️ Postponed',
                                'cancelled' => '❌ Cancelled',
                            ])
                            ->default('scheduled')
                            ->required(),
                        Select::make('stage')
                            ->options([
                                'group' => 'Group Stage',
                                'round_of_32' => 'Round of 32',
                                'round_of_16' => 'Round of 16',
                                'quarter_final' => 'Quarter Final',
                                'semi_final' => 'Semi Final',
                                'third_place' => 'Third Place',
                                'final' => 'Final',
                            ])
                            ->default('group')
                            ->required(),
                        Select::make('group')
                            ->options([
                                'A' => 'Group A', 'B' => 'Group B', 'C' => 'Group C', 'D' => 'Group D',
                                'E' => 'Group E', 'F' => 'Group F', 'G' => 'Group G', 'H' => 'Group H',
                                'I' => 'Group I', 'J' => 'Group J', 'K' => 'Group K', 'L' => 'Group L',
                            ])
                            ->visible(fn ($get) => $get('stage') === 'group'),
                        TextInput::make('matchday')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(3)
                            ->visible(fn ($get) => $get('stage') === 'group'),
                    ])->columns(4),

                Section::make('Score')
                    ->schema([
                        TextInput::make('home_score')
                            ->label('Home Score')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('away_score')
                            ->label('Away Score')
                            ->numeric()
                            ->minValue(0),
                        TextInput::make('home_score_penalties')
                            ->label('Home Penalties')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn ($get) => $get('stage') !== 'group'),
                        TextInput::make('away_score_penalties')
                            ->label('Away Penalties')
                            ->numeric()
                            ->minValue(0)
                            ->visible(fn ($get) => $get('stage') !== 'group'),
                    ])->columns(4),

                Section::make('AI Analysis')
                    ->schema([
                        Textarea::make('ai_analysis')
                            ->label('Tactical Analysis')
                            ->helperText('AI-generated pre-match analysis')
                            ->rows(4),
                    ])->collapsible(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('start_time')
                    ->dateTime('M d, Y H:i')
                    ->sortable(),
                TextColumn::make('homeTeam.iso_code')
                    ->label('Home')
                    ->badge()
                    ->color('success'),
                TextColumn::make('score_display')
                    ->label('Score')
                    ->alignCenter(),
                TextColumn::make('awayTeam.iso_code')
                    ->label('Away')
                    ->badge()
                    ->color('danger'),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'scheduled' => 'gray',
                        'live' => 'danger',
                        'finished' => 'success',
                        'postponed' => 'warning',
                        'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('stage')
                    ->badge(),
                TextColumn::make('group')
                    ->badge()
                    ->color('info'),
                TextColumn::make('stadium.name')
                    ->toggleable(),
            ])
            ->filters([
                SelectFilter::make('status'),
                SelectFilter::make('stage'),
                SelectFilter::make('group'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('start_time');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListGames::route('/'),
            'create' => Pages\CreateGame::route('/create'),
            'edit' => Pages\EditGame::route('/{record}/edit'),
        ];
    }
}
