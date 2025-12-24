<?php

namespace App\Filament\Resources;

use App\Filament\Resources\StadiumResource\Pages;
use App\Models\Stadium;
use BackedEnum;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Section;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class StadiumResource extends Resource
{
    protected static ?string $model = Stadium::class;

    public static function getNavigationIcon(): string|BackedEnum|null
    {
        return 'heroicon-o-building-office-2';
    }

    public static function getNavigationSort(): ?int
    {
        return 3;
    }

    public static function getNavigationGroup(): ?string
    {
        return 'Tournament';
    }

    public static function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Venue Info')
                    ->schema([
                        TextInput::make('name')
                            ->required()
                            ->maxLength(255),
                        TextInput::make('city')
                            ->required()
                            ->maxLength(100),
                        Select::make('country')
                            ->options([
                                'USA' => '🇺🇸 United States',
                                'MEX' => '🇲🇽 Mexico',
                                'CAN' => '🇨🇦 Canada',
                            ])
                            ->required(),
                        TextInput::make('capacity')
                            ->numeric()
                            ->minValue(1000),
                    ])->columns(2),

                Section::make('Location')
                    ->schema([
                        TextInput::make('latitude')
                            ->numeric()
                            ->step(0.0000001),
                        TextInput::make('longitude')
                            ->numeric()
                            ->step(0.0000001),
                        Select::make('timezone')
                            ->options([
                                'America/New_York' => 'Eastern Time (ET)',
                                'America/Chicago' => 'Central Time (CT)',
                                'America/Los_Angeles' => 'Pacific Time (PT)',
                                'America/Mexico_City' => 'Mexico City Time',
                                'America/Monterrey' => 'Monterrey Time',
                                'America/Toronto' => 'Toronto Time',
                                'America/Vancouver' => 'Vancouver Time',
                            ])
                            ->default('America/New_York'),
                    ])->columns(3),

                Section::make('Media')
                    ->schema([
                        FileUpload::make('image_url')
                            ->label('Stadium Image')
                            ->image()
                            ->directory('stadiums')
                            ->imagePreviewHeight('200'),
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                ImageColumn::make('image_url')
                    ->label('Image')
                    ->size(60),
                TextColumn::make('name')
                    ->searchable()
                    ->sortable(),
                TextColumn::make('city')
                    ->searchable(),
                TextColumn::make('country')
                    ->badge()
                    ->color(fn ($state) => match ($state) {
                        'USA' => 'info',
                        'MEX' => 'success',
                        'CAN' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('capacity')
                    ->numeric()
                    ->sortable(),
                TextColumn::make('timezone')
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                SelectFilter::make('country')
                    ->options([
                        'USA' => '🇺🇸 United States',
                        'MEX' => '🇲🇽 Mexico',
                        'CAN' => '🇨🇦 Canada',
                    ]),
            ])
            ->actions([
                EditAction::make(),
            ])
            ->bulkActions([
                DeleteBulkAction::make(),
            ])
            ->defaultSort('capacity', 'desc');
    }

    public static function getRelations(): array
    {
        return [];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListStadiums::route('/'),
            'create' => Pages\CreateStadium::route('/create'),
            'edit' => Pages\EditStadium::route('/{record}/edit'),
        ];
    }
}
