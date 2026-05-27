<?php

namespace App\Filament\Resources\Subscriptions;

use App\Filament\Resources\Subscriptions\Pages\CreateSubscription;
use App\Filament\Resources\Subscriptions\Pages\EditSubscription;
use App\Filament\Resources\Subscriptions\Pages\ListSubscriptions;
use App\Models\Plan;
use App\Models\Subscription;
use App\Models\User;
use BackedEnum;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Facades\Auth;
use UnitEnum;

class SubscriptionResource extends Resource
{
    protected static ?string $model = Subscription::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    protected static ?string $navigationLabel = 'Subscriptions';

    protected static string|UnitEnum|null $navigationGroup = 'SaaS Management';

    protected static ?int $navigationSort = 2;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Subscription Details')
                ->columns(2)
                ->schema([
                    Select::make('user_id')
                        ->label('User')
                        ->relationship('user', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('plan_id')
                        ->label('Plan')
                        ->relationship('plan', 'name')
                        ->searchable()
                        ->preload()
                        ->required(),
                    Select::make('status')
                        ->options([
                            'active'    => 'Active',
                            'expired'   => 'Expired',
                            'cancelled' => 'Cancelled',
                        ])
                        ->required()
                        ->default('active'),
                    Select::make('granted_by')
                        ->label('Granted By')
                        ->relationship('grantedBy', 'name')
                        ->searchable()
                        ->preload()
                        ->default(fn () => Auth::id())
                        ->helperText('Admin who assigned this plan'),
                    DateTimePicker::make('starts_at')
                        ->label('Starts At')
                        ->default(now())
                        ->required(),
                    DateTimePicker::make('ends_at')
                        ->label('Ends At (leave blank for no expiry)')
                        ->nullable(),
                    Textarea::make('notes')
                        ->rows(3)->columnSpanFull()
                        ->placeholder('Internal notes about this subscription...'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('created_at', 'desc')
            ->columns([
                TextColumn::make('user.name')
                    ->label('User')
                    ->searchable()->sortable()->weight('bold'),
                TextColumn::make('user.email')
                    ->label('Email')
                    ->searchable()->copyable()
                    ->icon('heroicon-o-envelope'),
                TextColumn::make('plan.name')
                    ->label('Plan')
                    ->badge()
                    ->color(fn ($record) => match($record->plan?->slug) {
                        'free-trial'  => 'gray',
                        'starter'     => 'info',
                        'pro'         => 'warning',
                        'enterprise'  => 'success',
                        default       => 'gray',
                    }),
                TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'active' => 'success',
                        'expired', 'cancelled' => 'danger',
                        default => 'gray',
                    }),
                TextColumn::make('starts_at')
                    ->label('Started')
                    ->date('d M Y')->sortable(),
                TextColumn::make('ends_at')
                    ->label('Expires')
                    ->date('d M Y')
                    ->placeholder('No Expiry')
                    ->sortable(),
                TextColumn::make('days_remaining')
                    ->label('Days Left')
                    ->state(fn (Subscription $record) => $record->daysRemaining())
                    ->placeholder('∞')
                    ->badge()
                    ->color(fn ($state) => match(true) {
                        $state === null         => 'gray',
                        $state <= 7             => 'danger',
                        $state <= 30            => 'warning',
                        default                 => 'success',
                    }),
                TextColumn::make('grantedBy.name')
                    ->label('Granted By')
                    ->placeholder('—'),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->options([
                        'active'    => 'Active',
                        'expired'   => 'Expired',
                        'cancelled' => 'Cancelled',
                    ]),
                SelectFilter::make('plan')
                    ->relationship('plan', 'name'),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListSubscriptions::route('/'),
            'create' => CreateSubscription::route('/create'),
            'edit'   => EditSubscription::route('/{record}/edit'),
        ];
    }
}
