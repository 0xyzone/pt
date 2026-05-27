<?php

namespace App\Filament\Widgets;

use App\Models\DemoRequest;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Widgets\TableWidget as BaseWidget;
use App\Filament\Resources\DemoRequests\DemoRequestResource;

class LatestDemoRequestsWidget extends BaseWidget
{
    protected static ?int $sort = 3;
    protected int | string | array $columnSpan = 'full';

    public function table(Table $table): Table
    {
        return $table
            ->query(
                DemoRequest::query()
                    ->where('status', 'new')
                    ->latest()
                    ->limit(5)
            )
            ->columns([
                Tables\Columns\TextColumn::make('name')
                    ->label('Name')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('organization')
                    ->label('Organization')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('email')
                    ->label('Email')
                    ->sortable()
                    ->searchable(),
                Tables\Columns\TextColumn::make('status')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'new' => 'warning',
                        'contacted' => 'info',
                        'converted' => 'success',
                        'rejected' => 'danger',
                        default => 'gray',
                    }),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable(),
            ])
            ->recordUrl(
                fn (DemoRequest $record): string => DemoRequestResource::getUrl('edit', ['record' => $record])
            );
    }
}
