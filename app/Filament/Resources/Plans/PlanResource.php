<?php

namespace App\Filament\Resources\Plans;

use App\Filament\Resources\Plans\Pages\CreatePlan;
use App\Filament\Resources\Plans\Pages\EditPlan;
use App\Filament\Resources\Plans\Pages\ListPlans;
use App\Models\Plan;
use BackedEnum;
use Filament\Actions\DeleteAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Support\Str;
use UnitEnum;

class PlanResource extends Resource
{
    protected static ?string $model = Plan::class;

    protected static string|BackedEnum|null $navigationIcon = Heroicon::OutlinedSquaresPlus;

    protected static ?string $navigationLabel = 'Subscription Plans';

    protected static string|UnitEnum|null $navigationGroup = 'SaaS Management';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make('Plan Details')
                ->columns(2)
                ->schema([
                    TextInput::make('name')
                        ->required()->maxLength(80)
                        ->live(onBlur: true)
                        ->afterStateUpdated(fn ($state, callable $set) => $set('slug', Str::slug($state))),
                    TextInput::make('slug')
                        ->required()->maxLength(80)->unique(ignoreRecord: true)
                        ->helperText('Auto-generated from name. Must be unique.'),
                    Textarea::make('description')
                        ->rows(2)->columnSpanFull(),
                    TextInput::make('price_display')
                        ->label('Price Display')
                        ->placeholder('e.g. NPR 2,500/mo or Free')
                        ->maxLength(60),
                    TextInput::make('duration_value')
                        ->label('Duration Value')
                        ->numeric()
                        ->placeholder('e.g. 1, 6, 12. Leave empty for Lifetime plan.'),
                    Select::make('duration_period')
                        ->label('Duration Period')
                        ->options([
                            'days' => 'Days',
                            'weeks' => 'Weeks',
                            'months' => 'Months',
                            'years' => 'Years',
                        ])
                        ->placeholder('Select Period'),
                    TextInput::make('sort_order')
                        ->numeric()->default(0),
                    Toggle::make('is_active')
                        ->label('Active (visible to admin)')
                        ->default(true),
                ]),

            Section::make('Feature Flags')
                ->description('Control what this plan allows. Use -1 for unlimited counts.')
                ->schema([
                    Grid::make(3)->schema([
                        TextInput::make('features.max_tournaments')
                            ->label('Max Tournaments')
                            ->numeric()->default(1)
                            ->helperText('-1 = unlimited'),
                        TextInput::make('features.max_teams')
                            ->label('Max Teams per Tournament')
                            ->numeric()->default(8)
                            ->helperText('-1 = unlimited'),
                        TextInput::make('features.max_matches')
                            ->label('Max Matches per Tournament')
                            ->numeric()->default(10)
                            ->helperText('-1 = unlimited'),
                    ]),
                    Grid::make(3)->schema([
                        Toggle::make('features.obs_overlays')
                            ->label('OBS Overlays')->default(true),
                        Toggle::make('features.websocket_sync')
                            ->label('Real-Time WebSocket Sync')->default(true),
                        Toggle::make('features.roadmap_overlay')
                            ->label('Tournament Roadmap Overlay')->default(false),
                    ]),
                    Grid::make(3)->schema([
                        Toggle::make('features.casters_management')
                            ->label('Casters Management')->default(false),
                        Toggle::make('features.player_management')
                            ->label('Player Management')->default(false),
                        Toggle::make('features.custom_branding')
                            ->label('Custom Branding')->default(false),
                    ]),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->defaultSort('sort_order')
            ->reorderable('sort_order')
            ->columns([
                TextColumn::make('sort_order')->label('#')->sortable(),
                TextColumn::make('name')->weight('bold')->searchable(),
                TextColumn::make('slug')->badge()->color('gray'),
                TextColumn::make('price_display')->label('Price'),
                TextColumn::make('duration_value')
                    ->label('Duration')
                    ->formatStateUsing(fn ($record) => $record->duration_value ? "{$record->duration_value} " . ucfirst($record->duration_period) : 'Lifetime')
                    ->badge()->color('warning'),
                TextColumn::make('subscriptions_count')
                    ->label('Subscribers')
                    ->counts('subscriptions')
                    ->badge()->color('info'),
                IconColumn::make('is_active')
                    ->label('Active')
                    ->boolean(),
                TextColumn::make('updated_at')->label('Last Updated')->since(),
            ])
            ->actions([
                EditAction::make(),
                DeleteAction::make(),
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index'  => ListPlans::route('/'),
            'create' => CreatePlan::route('/create'),
            'edit'   => EditPlan::route('/{record}/edit'),
        ];
    }
}
