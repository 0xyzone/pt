<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Tournament;
use App\Models\TournamentSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Repeater\TableColumn;
use Filament\Forms\Components\TextInput;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class EditTournamentSetting extends Page
{
    public int|string|null $tournamentId;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCog;
    protected static string $resource = TournamentResource::class;
    protected string $view = 'filament.maidan.resources.tournaments.pages.tournament-setting';

    public function getSubNavigation(): array
    {
        return TournamentResource::getRecordSubNavigation($this);
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => Tournament::findOrFail($this->tournamentId),
        ];
    }

    /**
     * @var array<string, mixed> | null
     */
    public ?array $data = [];

    public function mount(int|string|null $record): void
    {
        $this->tournamentId = $record;
        // dd($tournamentId);
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('kill_points')
                        ->label('Kill Points')
                        ->required()
                        ->default(0),
                    Actions::make([
                        Action::make('populatePositions')
                            ->label('Auto-Populate Positions')
                            ->color('info')
                            ->icon('heroicon-o-sparkles')
                            ->form([
                                TextInput::make('count')
                                    ->label('Number of Positions')
                                    ->numeric()
                                    ->default(16)
                                    ->required(),
                            ])
                            ->action(function (array $data, $set) {
                                $count = (int) $data['count'];
                                $items = [];
                                for ($i = 1; $i <= $count; $i++) {
                                    $items[] = [
                                        'placement' => $i,
                                        'points' => 0,
                                    ];
                                }
                                $set('tournamentSettingPlacementPoints', $items);
                            }),
                    ]),
                    Repeater::make('tournamentSettingPlacementPoints')
                        ->relationship()
                        ->columns(2)
                        ->grid(2)
                        ->label('Placement Points')
                        ->table([
                            TableColumn::make('Placement'),
                            TableColumn::make('Points'),
                        ])
                        ->schema([
                            TextInput::make('placement')
                                ->label('Position')
                                ->numeric()
                                ->required(),
                            TextInput::make('points')
                                ->label('Points')
                                ->numeric()
                                ->required()
                                ->default(0),
                        ]),
                ])
                    ->livewireSubmitHandler('save')
                    ->footer([
                        Actions::make([
                            Action::make('save')
                                ->label('Save')
                                ->submit('save'),
                        ]),
                    ])
            ])
            ->record($this->getRecord())
            ->statePath('data');
    }

    public function save(): void
    {
        $data = $this->form->getState();
        $data['tournament_id'] = $this->tournamentId;
        // dd($data);
        $record = $this->getRecord();

        if (!$record) {
            $record = new TournamentSetting();
        }

        $record->fill($data);
        $record->save();

        if ($record->wasRecentlyCreated) {
            $this->form->record($record)->saveRelationships();
        }

        Notification::make()
            ->title('Tournament settings saved successfully')
            ->success()
            ->send();
    }

    public function getRecord(): TournamentSetting // Remove the ? so it always returns an object
    {
        // Try to find the existing setting
        $record = TournamentSetting::where('tournament_id', $this->tournamentId)->first();

        // If it doesn't exist, return a new instance with the ID pre-filled
        return $record ?? new TournamentSetting(['tournament_id' => $this->tournamentId]);
    }
}
