<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Tournament;
use App\Models\TournamentRoadmap;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

/**
 * @property-read Schema $form
 */
class EditTournamentRoadmap extends Page
{
    public int|string|null $tournamentId;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedMap;
    protected static string $resource = TournamentResource::class;
    protected static ?int $navigationSort = 4;
    protected static ?string $navigationLabel = 'Edit Tournament Roadmap';
    protected string $view = 'filament.maidan.resources.tournaments.pages.tournament-roadmap';

    protected static function getTournamentId(): ?string
    {
        $tournament = request()->route('tournament') ?? request()->route('record');
        
        return $tournament instanceof \Illuminate\Database\Eloquent\Model ? $tournament->getKey() : $tournament;
    }

    public static function getNavigationBadge(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentRoadmap::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = self::getTournamentId();
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentRoadmap::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : 'warning';
    }

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
        $this->form->fill($this->getRecord()?->attributesToArray());
    }

    public function form(Schema $schema): Schema
    {
        return $schema
            ->components([
                Form::make([
                    TextInput::make('title')
                        ->label('Roadmap Title')
                        ->required()
                        ->default('Tournament Roadmap')
                        ->maxLength(255),
                    Textarea::make('description')
                        ->label('General Description')
                        ->nullable()
                        ->rows(2)
                        ->maxLength(1000),
                    Repeater::make('steps')
                        ->label('Roadmap Phases / Steps')
                        ->schema([
                            TextInput::make('name')
                                ->label('Phase Name')
                                ->required()
                                ->placeholder('e.g., Registrations')
                                ->maxLength(255),
                            DatePicker::make('start_date')
                                ->label('Start Date')
                                ->native(false)
                                ->nullable(),
                            DatePicker::make('end_date')
                                ->label('End Date')
                                ->native(false)
                                ->nullable(),
                            Select::make('status')
                                ->label('Status')
                                ->options([
                                    'upcoming' => 'Upcoming',
                                    'active' => 'Active',
                                    'completed' => 'Completed',
                                    'cancelled' => 'Cancelled',
                                ])
                                ->default('upcoming')
                                ->required(),
                            TextInput::make('description')
                                ->label('Details / Goals')
                                ->nullable()
                                ->placeholder('e.g., Top 16 qualify')
                                ->maxLength(500),
                        ])
                        ->columns(5)
                        ->reorderable(true)
                        ->addActionLabel('Add Roadmap Step')
                        ->deleteAction(fn ($action) => $action->icon('heroicon-o-trash')->color('danger')),
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
        $record = $this->getRecord();

        if (!$record) {
            $record = new TournamentRoadmap();
        }

        $record->fill($data);
        $record->save();

        $tournament = Tournament::find($this->tournamentId);
        if ($tournament && $tournament->user_id) {
            try {
                broadcast(new \App\Events\TournamentRoadmapUpdated($tournament->user_id));
            } catch (\Throwable $e) {
                \Illuminate\Support\Facades\Log::warning('[Broadcasting] Failed to broadcast roadmap: ' . $e->getMessage());
            }
        }

        Notification::make()
            ->title('Tournament roadmap saved successfully')
            ->success()
            ->send();
    }

    public function getRecord(): TournamentRoadmap
    {
        $record = TournamentRoadmap::where('tournament_id', $this->tournamentId)->first();
        return $record ?? new TournamentRoadmap(['tournament_id' => $this->tournamentId]);
    }
}
