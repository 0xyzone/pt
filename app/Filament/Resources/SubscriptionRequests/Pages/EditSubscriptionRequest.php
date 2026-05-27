<?php

namespace App\Filament\Resources\SubscriptionRequests\Pages;

use App\Filament\Resources\SubscriptionRequests\SubscriptionRequestResource;
use App\Models\Subscription;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Auth;

class EditSubscriptionRequest extends EditRecord
{
    protected static string $resource = SubscriptionRequestResource::class;

    protected function getHeaderActions(): array
    {
        return [
            // Approve Action
            Action::make('approve')
                ->label('Approve')
                ->icon('heroicon-m-check')
                ->color('success')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->requiresConfirmation()
                ->action(function (): void {
                    $record = $this->record;

                    // Deactivate old active subscriptions for the user
                    Subscription::where('user_id', $record->user_id)
                        ->where('status', 'active')
                        ->update(['status' => 'expired']);

                    // Create new active subscription
                    Subscription::create([
                        'user_id' => $record->user_id,
                        'plan_id' => $record->plan_id,
                        'status' => 'active',
                        'starts_at' => now(),
                        'ends_at' => $record->plan->calculateEndDate(),
                        'granted_by' => Auth::id(),
                        'notes' => 'Automatically approved via request.',
                    ]);

                    // Update the request status
                    $record->update([
                        'status' => 'approved',
                        'admin_notes' => 'Approved on ' . now()->format('Y-m-d H:i') . '.',
                    ]);

                    // Send database notification to the user
                    Notification::make()
                        ->title('Subscription Request Approved!')
                        ->body("Your request for the **{$record->plan->name}** plan has been approved and activated.")
                        ->success()
                        ->sendToDatabase($record->user);

                    Notification::make()
                        ->title('Subscription approved and activated successfully.')
                        ->success()
                        ->send();

                    // Refresh form
                    $this->refreshFormData(['status', 'admin_notes']);
                }),

            // Reject Action
            Action::make('reject')
                ->label('Reject')
                ->icon('heroicon-m-x-mark')
                ->color('danger')
                ->visible(fn (): bool => $this->record->status === 'pending')
                ->form([
                    Textarea::make('admin_notes')
                        ->label('Rejection Reason')
                        ->required()
                        ->placeholder('e.g., Payment screenshot was not valid.'),
                ])
                ->action(function (array $data): void {
                    $record = $this->record;

                    $record->update([
                        'status' => 'rejected',
                        'admin_notes' => $data['admin_notes'],
                    ]);

                    // Send database notification to the user
                    Notification::make()
                        ->title('Subscription Request Rejected')
                        ->body("Your request for the **{$record->plan->name}** plan has been rejected. Reason: " . $data['admin_notes'])
                        ->danger()
                        ->sendToDatabase($record->user);

                    Notification::make()
                        ->title('Subscription request rejected.')
                        ->danger()
                        ->send();

                    // Refresh form
                    $this->refreshFormData(['status', 'admin_notes']);
                }),

            DeleteAction::make(),
        ];
    }
}
