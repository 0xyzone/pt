<?php

namespace App\Filament\Maidan\Resources\Tournaments\Pages;

use App\Filament\Maidan\Resources\Tournaments\Resources\TournamentTeams\TournamentTeamResource;
use App\Filament\Maidan\Resources\Tournaments\TournamentResource;
use App\Models\Player;
use App\Models\TournamentTeam;
use BackedEnum;
use Filament\Actions\Action;
use Filament\Actions\CreateAction;
use Filament\Forms\Components\FileUpload;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ListRecords;
use Filament\Support\Icons\Heroicon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ListTournamentTeams extends ListRecords
{
    protected static string $resource = TournamentTeamResource::class;
    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedUsers;
    protected static ?string $navigationLabel = 'Manage Teams';

    public function getSubNavigation(): array
    {
        return TournamentResource::getRecordSubNavigation($this);
    }

    public function getSubNavigationParameters(): array
    {
        return [
            'record' => $this->getParentRecord(),
        ];
    }
    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
            Action::make('downloadSample')
                ->label('Download CSV Template')
                ->icon('heroicon-o-document-arrow-down')
                ->color('info')
                ->action(fn () => response()->streamDownload(
                    function () {
                        $csv = "team_name,short_name,contact_number,contact_email,whatsapp_number,player_name,player_ign,player_uid,player_role\n";
                        $csv .= "Alpha Team,ALPH,+123456789,alpha@example.com,+123456789,John Doe,AlphaPlayer1,UID12345,igl\n";
                        $csv .= "Alpha Team,ALPH,+123456789,alpha@example.com,+123456789,Jane Smith,AlphaPlayer2,UID67890,player\n";
                        $csv .= "Alpha Team,ALPH,+123456789,alpha@example.com,+123456789,Bob Johnson,AlphaPlayer3,UID11111,substitute\n";
                        $csv .= "Beta Esports,BETA,+987654321,beta@example.com,+987654321,Alice Brown,BetaPlayer1,UID22222,igl\n";
                        $csv .= "Beta Esports,BETA,+987654321,beta@example.com,+987654321,Charlie Green,BetaPlayer2,UID33333,manager\n";
                        echo $csv;
                    },
                    'tournament_teams_and_players_template.csv'
                )),
            Action::make('import')
                ->label('Import Teams & Players')
                ->icon('heroicon-o-document-arrow-up')
                ->color('success')
                ->form([
                    FileUpload::make('file')
                        ->label('Select CSV File')
                        ->required()
                        ->disk('local')
                        ->directory('imports')
                        ->acceptedFileTypes(['text/csv', 'text/plain', 'application/vnd.ms-excel']),
                ])
                ->action(function (array $data, ListTournamentTeams $livewire) {
                    $filePath = Storage::disk('local')->path($data['file']);
                    
                    if (!file_exists($filePath)) {
                        Notification::make()
                            ->title('File not found')
                            ->danger()
                            ->send();
                        return;
                    }

                    $tournament = $livewire->getParentRecord();
                    if (!$tournament) {
                        Notification::make()
                            ->title('Tournament context missing')
                            ->danger()
                            ->send();
                        return;
                    }
                    $tournamentId = $tournament->id;

                    try {
                        DB::beginTransaction();

                        $file = fopen($filePath, 'r');
                        $header = fgetcsv($file);
                        
                        if (!$header) {
                            throw new \Exception("The CSV file is empty.");
                        }

                        // Normalize headers (trim, lowercase)
                        $header = array_map(fn($h) => strtolower(trim($h)), $header);

                        // Required columns check
                        $required = ['team_name', 'player_ign'];
                        foreach ($required as $col) {
                            if (!in_array($col, $header)) {
                                throw new \Exception("Missing required column in CSV: '{$col}'. Please use the template format.");
                            }
                        }

                        $successCount = 0;
                        $teamsCreated = [];

                        while (($row = fgetcsv($file)) !== false) {
                            // Combine header with row
                            if (count($header) !== count($row)) {
                                continue; // Skip malformed rows
                            }
                            
                            $dataRow = array_combine($header, $row);

                            $teamName = trim($dataRow['team_name'] ?? '');
                            $playerIgn = trim($dataRow['player_ign'] ?? '');

                            if (empty($teamName) || empty($playerIgn)) {
                                continue; // Skip empty rows
                            }

                            // Find or create the team for this tournament
                            $teamKey = strtolower($teamName);
                            if (!isset($teamsCreated[$teamKey])) {
                                $team = TournamentTeam::firstOrCreate([
                                    'tournament_id' => $tournamentId,
                                    'name' => $teamName,
                                ], [
                                    'short_name' => trim($dataRow['short_name'] ?? null),
                                    'contact_number' => trim($dataRow['contact_number'] ?? null),
                                    'contact_email' => trim($dataRow['contact_email'] ?? null),
                                    'whatsapp_number' => trim($dataRow['whatsapp_number'] ?? null),
                                ]);
                                $teamsCreated[$teamKey] = $team;
                            } else {
                                $team = $teamsCreated[$teamKey];
                            }

                            // Add or update the player on the team
                            $playerRole = strtolower(trim($dataRow['player_role'] ?? 'player'));
                            if (!in_array($playerRole, ['player', 'igl', 'substitute', 'manager'])) {
                                $playerRole = 'player';
                            }

                            Player::updateOrCreate([
                                'tournament_team_id' => $team->id,
                                'ign' => $playerIgn,
                            ], [
                                'name' => trim($dataRow['player_name'] ?? null),
                                'in_game_id' => trim($dataRow['player_uid'] ?? $dataRow['player_id'] ?? null),
                                'role' => $playerRole,
                            ]);

                            $successCount++;
                        }

                        fclose($file);
                        DB::commit();

                        Notification::make()
                            ->title('Import successful!')
                            ->body("Successfully imported {$successCount} players across " . count($teamsCreated) . " teams.")
                            ->success()
                            ->send();

                    } catch (\Exception $e) {
                        DB::rollBack();
                        Notification::make()
                            ->title('Import failed')
                            ->body($e->getMessage())
                            ->danger()
                            ->send();
                    }
                })
        ];
    }
}
