<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Tournament;
use App\Models\TournamentSetting;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Forms\Components\FileUpload;
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
    protected static ?int $navigationSort = 3;
    protected static ?string $navigationLabel = 'Edit Tournament Settings';
    protected string $view = 'filament.maidan.resources.tournaments.pages.tournament-setting';

    public static function getNavigationBadge(): ?string
    {
        // Show a warning badge if this tournament has no settings configured yet.
        // We read the tournamentId from the current route parameter.
        $tournamentId = request()->route('record');
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentSetting::where('tournament_id', $tournamentId)->exists();
        return $exists ? null : '!';
    }

    public static function getNavigationBadgeColor(): ?string
    {
        $tournamentId = request()->route('record');
        if (!$tournamentId) {
            return null;
        }
        $exists = TournamentSetting::where('tournament_id', $tournamentId)->exists();
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

                        Action::make('downloadSample')
                            ->label('Download Sample CSV')
                            ->color('gray')
                            ->icon('heroicon-o-document-arrow-down')
                            ->action(function () {
                                return response()->streamDownload(function () {
                                    echo "placement,points\n";
                                    echo "1,15\n";
                                    echo "2,12\n";
                                    echo "3,10\n";
                                    echo "4,8\n";
                                    echo "5,6\n";
                                    echo "6,4\n";
                                    echo "7,2\n";
                                    echo "8,1\n";
                                    for ($i = 9; $i <= 16; $i++) {
                                        echo "$i,0\n";
                                    }
                                }, 'sample-placement-points.csv', [
                                    'Content-Type' => 'text/csv',
                                ]);
                            }),

                        Action::make('importCsv')
                            ->label('Import from CSV')
                            ->color('success')
                            ->icon('heroicon-o-arrow-up-tray')
                            ->form([
                                FileUpload::make('file')
                                    ->label('Upload CSV File')
                                    ->acceptedFileTypes(['text/csv', 'text/plain', 'application/csv', 'text/comma-separated-values'])
                                    ->disk('public')
                                    ->directory('temp-csv-imports')
                                    ->visibility('public')
                                    ->required(),
                            ])
                            ->action(function (array $data, $set) {
                                $contents = \Illuminate\Support\Facades\Storage::disk('public')->get($data['file']);
                                
                                $stream = fopen('php://temp', 'r+');
                                fwrite($stream, $contents);
                                rewind($stream);

                                $headers = null;
                                $items = [];
                                while (($row = fgetcsv($stream)) !== false) {
                                    if (empty($row) || (count($row) === 1 && is_null($row[0]))) {
                                        continue;
                                    }
                                    
                                    if (!$headers) {
                                        $headers = array_map('strtolower', array_map('trim', $row));
                                        continue;
                                    }
                                    
                                    if (count($headers) > count($row)) {
                                        $row = array_pad($row, count($headers), '');
                                    } elseif (count($headers) < count($row)) {
                                        $row = array_slice($row, 0, count($headers));
                                    }
                                    
                                    $assoc = array_combine($headers, $row);
                                    
                                    $placement = null;
                                    $points = null;
                                    
                                    foreach ($assoc as $key => $val) {
                                        if (in_array($key, ['placement', 'position', 'rank', 'place'])) {
                                            $placement = (int)trim($val);
                                        }
                                        if (in_array($key, ['points', 'point', 'pts'])) {
                                            $points = (int)trim($val);
                                        }
                                    }
                                    
                                    if (is_null($placement) && isset($row[0])) {
                                        $placement = (int)trim($row[0]);
                                    }
                                    if (is_null($points) && isset($row[1])) {
                                        $points = (int)trim($row[1]);
                                    }
                                    
                                    if (!is_null($placement) && !is_null($points)) {
                                        $items[] = [
                                            'placement' => $placement,
                                            'points' => $points,
                                        ];
                                    }
                                }
                                fclose($stream);
                                
                                \Illuminate\Support\Facades\Storage::disk('public')->delete($data['file']);

                                if (empty($items)) {
                                    Notification::make()
                                        ->title('Import Failed')
                                        ->body('No valid placement and points data was found in the file.')
                                        ->danger()
                                        ->send();
                                    return;
                                }

                                $set('tournamentSettingPlacementPoints', $items);

                                Notification::make()
                                    ->title('Points Imported')
                                    ->body(sprintf('Successfully imported %d positions.', count($items)))
                                    ->success()
                                    ->send();
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
