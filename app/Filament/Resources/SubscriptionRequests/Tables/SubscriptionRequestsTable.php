<?php

namespace App\Filament\Resources\SubscriptionRequests\Tables;

use App\Models\Subscription;
use App\Models\SubscriptionRequest;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Textarea;
use Filament\Notifications\Notification;
use Filament\Actions\Action;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use TinusG\FilamentHoverImageColumn\HoverImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;

class SubscriptionRequestsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()
                    ->copyable(),
                TextColumn::make('plan.name')
                    ->label('Requested Plan')
                    ->badge()
                    ->color('info')
                    ->searchable(),
                HoverImageColumn::make('screenshot_path')
                    ->label('Screenshot')
                    ->disk('public')
                    ->square()
                    ->imageSize(50),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'pending' => 'warning',
                        'approved' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    })
                    ->sortable(),
                TextColumn::make('created_at')
                    ->label('Submitted')
                    ->dateTime('d M Y H:i')
                    ->sortable(),
            ])
            ->filters([
                //
            ])
            ->actions([
                // Approve Action
                Action::make('approve')
                    ->label('Approve')
                    ->icon('heroicon-m-check')
                    ->color('success')
                    ->visible(fn (SubscriptionRequest $record): bool => $record->status === 'pending')
                    ->requiresConfirmation()
                    ->action(function (SubscriptionRequest $record): void {
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
                            'ends_at' => null,
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
                    }),

                // Reject Action
                Action::make('reject')
                    ->label('Reject')
                    ->icon('heroicon-m-x-mark')
                    ->color('danger')
                    ->visible(fn (SubscriptionRequest $record): bool => $record->status === 'pending')
                    ->form([
                        Textarea::make('admin_notes')
                            ->label('Rejection Reason')
                            ->required()
                            ->placeholder('e.g., Payment screenshot was not valid.'),
                    ])
                    ->action(function (SubscriptionRequest $record, array $data): void {
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
                    }),

                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
