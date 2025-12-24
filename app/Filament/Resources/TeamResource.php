<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TeamResource\Pages;
use App\Models\Team;
use BackedEnum;
use Filament\Forms\Components\ColorPicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Filters\TernaryFilter;
use Filament\Tables\Table;

class TeamResource extends Resource
{
    protected static ?string $model = Team::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-flag';
    }

    public static function getNavigationSort(): ?int
    {
        return 1;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Tournament';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Team Info')
                    ->schema([
                        TextInput::make('name.en')
                            ->label('Name (English)')
                            ->required(),
                        TextInput::make('name.nl')
                            ->label('Name (Dutch)'),
                        TextInput::make('iso_code')
                            ->label('ISO Code')
                            ->required()
                            ->maxLength(3)
                            ->unique(ignoreRecord: true),
                        Select::make('group')
                            ->options([
                                'A' => 'Group A', 'B' => 'Group B', 'C' => 'Group C', 'D' => 'Group D',
                                'E' => 'Group E', 'F' => 'Group F', 'G' => 'Group G', 'H' => 'Group H',
                                'I' => 'Group I', 'J' => 'Group J', 'K' => 'Group K', 'L' => 'Group L',
                            ])
                            ->searchable(),
                        Select::make('confederation')
                            ->options([
                                'UEFA' => 'UEFA (Europe)',
                                'CONMEBOL' => 'CONMEBOL (South America)',
                                'CONCACAF' => 'CONCACAF (North/Central America)',
                                'CAF' => 'CAF (Africa)',
                                'AFC' => 'AFC (Asia)',
                                'OFC' => 'OFC (Oceania)',
                            ])
                            ->searchable(),
                    ])->columns(2),

                Section::make('Appearance')
                    ->schema([
                        FileUpload::make('flag_url')
                            ->label('Flag')
                            ->image()
                            ->directory('flags')
                            ->imagePreviewHeight('60'),
                        ColorPicker::make('colors.primary')
                            ->label('Primary Color'),
                        ColorPicker::make('colors.secondary')
                            ->label('Secondary Color'),
                    ])->columns(3),

                Section::make('Status')
                    ->schema([
                        TextInput::make('fifa_ranking')
                            ->numeric()
                            ->minValue(1)
                            ->maxValue(211),
                        Toggle::make('is_placeholder')
                            ->label('Placeholder (Playoff TBD)')
                            ->helperText('Enable for teams not yet determined'),
                        TextInput::make('placeholder_label')
                            ->label('Placeholder Label')
                            ->placeholder('e.g., UEFA Playoff D Winner'),
                    ])->columns(3),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('flag_url')
                    ->label('Flag')
                    ->circular()
                    ->size(40),
                TextColumn::make('name')
                    ->label('Team')
                    ->formatStateUsing(fn ($record) => $record->display_name)
                    ->searchable(['name']),
                TextColumn::make('iso_code')
                    ->badge()
                    ->color('gray'),
                TextColumn::make('group')
                    ->badge()
                    ->color('primary'),
                TextColumn::make('confederation')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'UEFA' => 'info',
                        'CONMEBOL' => 'success',
                        'CONCACAF' => 'warning',
                        'CAF' => 'danger',
                        'AFC' => 'gray',
                        'OFC' => 'primary',
                        default => 'gray',
                    }),
                TextColumn::make('fifa_ranking')
                    ->numeric()
                    ->sortable(),
                IconColumn::make('is_placeholder')
                    ->boolean()
                    ->label('TBD'),
            ])
            ->filters([
                SelectFilter::make('group')
                    ->options([
                        'A' => 'Group A', 'B' => 'Group B', 'C' => 'Group C', 'D' => 'Group D',
                        'E' => 'Group E', 'F' => 'Group F', 'G' => 'Group G', 'H' => 'Group H',
                        'I' => 'Group I', 'J' => 'Group J', 'K' => 'Group K', 'L' => 'Group L',
                    ]),
                SelectFilter::make('confederation'),
                TernaryFilter::make('is_placeholder')
                    ->label('Placeholder Teams'),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('group');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTeams::route('/'),
            'create' => Pages\CreateTeam::route('/create'),
            'edit' => Pages\EditTeam::route('/{record}/edit'),
        ];
    }
}
