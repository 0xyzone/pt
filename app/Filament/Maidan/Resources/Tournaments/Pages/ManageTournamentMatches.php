<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\CreateAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DissociateAction;
use Filament\Actions\DissociateBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Pages\ManageRelatedRecords;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ManageTournamentMatches extends ManageRelatedRecords
{
    protected static string $resource = TournamentResource::class;

    protected static string $relationship = 'tournament_matches';

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('name')
                    ->required(),
                Select::make('tournament_round_id')
                    ->label('Round')
                    ->options(fn () => $this->getOwnerRecord()->tournamentRounds->pluck('name', 'id'))
                    ->nullable()
                    ->searchable(),
                // DatePicker::make('match_date'),
                // TimePicker::make('match_time'),
                Select::make('map')
                    ->options([
                        'erangle' => 'Erangle',
                        'miramar' => 'Miramar',
                        'sanhok' => 'Sanhok',
                        'rondo' => 'Rondo',
                        'vikendi' => 'Vikendi',
                        'taego' => 'Taego',
                        'deston' => 'Deston',
                        'karakin' => 'Karakin',
                        'paramo' => 'Paramo',
                        'haven' => 'Haven',
                    ])
                    ->default('erangle')
                    ->required(),
                Toggle::make('is_active')
                    ->label('Active Match')
                    ->default(false)
            ]);
    }

    public function table(Table $table): Table
    {
        return $table
            ->recordTitleAttribute('name')
            ->columns([
                TextColumn::make('name')
                    ->searchable(),
                TextColumn::make('tournamentRound.name')
                    ->label('Round')
                    ->searchable()
                    ->badge()
                    ->color('info'),
                // TextColumn::make('match_date')
                //     ->date()
                //     ->sortable(),
                // TextColumn::make('match_time')
                //     ->time()
                //     ->sortable(),
                TextColumn::make('map')
                    ->searchable(),
                TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->headerActions([
                CreateAction::make(),
                \Filament\Tables\Actions\Action::make('generate_matches')
                    ->label('Generate Round Matches')
                    ->icon('heroicon-o-sparkles')
                    ->color('success')
                    ->form([
                        Select::make('tournament_round_id')
                            ->label('Select Round')
                            ->options(fn () => $this->getOwnerRecord()->tournamentRounds->pluck('name', 'id'))
                            ->required(),
                    ])
                    ->action(function (array $data) {
                        $tournament = $this->getOwnerRecord();
                        $round = \App\Models\TournamentRound::find($data['tournament_round_id']);
                        if (!$round) {
                            return;
                        }

                        $maps = $round->maps ?? [];
                        if (empty($maps)) {
                            \Filament\Notifications\Notification::make()
                                ->title('No maps configured')
                                ->body('Please configure maps for this round in the tournament edit form first.')
                                ->warning()
                                ->send();
                            return;
                        }

                        $matchCount = 0;
                        foreach ($maps as $index => $mapItem) {
                            $mapName = $mapItem['map'] ?? 'erangle';
                            $matchIndex = $index + 1;

                            // Create the match without populating teams/players.
                            // Use the "Populate Teams" action on each match row to add teams and players.
                            $tournament->tournamentMatches()->create([
                                'tournament_round_id' => $round->id,
                                'name' => "{$round->name} - Match {$matchIndex}",
                                'map' => $mapName,
                                'is_active' => false,
                                'is_completed' => false,
                                'match_date' => now()->toDateString(),
                                'match_time' => now()->toTimeString(),
                            ]);

                            $matchCount++;
                        }

                        \Filament\Notifications\Notification::make()
                            ->title("Generated {$matchCount} matches")
                            ->body("Successfully created {$matchCount} empty matches for {$round->name}. Use the \"Populate Teams\" button on each match to add teams and players.")
                            ->success()
                            ->send();
                    })
            ])
            ->recordActions([
                EditAction::make(),
                DissociateAction::make(),
                DeleteAction::make(),
            ])
            ->toolbarActions([
                BulkActionGroup::make([
                    DissociateBulkAction::make(),
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
