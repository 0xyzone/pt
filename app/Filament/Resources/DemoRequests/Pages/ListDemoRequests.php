<?php

namespace App\Filament\Resources\DemoRequests\Pages;

use App\Filament\Resources\DemoRequests\DemoRequestResource;
use App\Models\DemoRequest;
use Filament\Resources\Pages\ListRecords;
use Filament\Schemas\Components\Tabs\Tab;
use Illuminate\Database\Eloquent\Builder;

class ListDemoRequests extends ListRecords
{
    protected static string $resource = DemoRequestResource::class;

    public function getTabs(): array
    {
        return [
            'all' => Tab::make('All')
                ->badge(DemoRequest::count()),

            'new' => Tab::make('New')
                ->badge(DemoRequest::where('status', 'new')->count())
                ->badgeColor('warning')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'new')),

            'contacted' => Tab::make('Contacted')
                ->badge(DemoRequest::where('status', 'contacted')->count())
                ->badgeColor('info')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'contacted')),

            'converted' => Tab::make('Converted')
                ->badge(DemoRequest::where('status', 'converted')->count())
                ->badgeColor('success')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'converted')),

            'rejected' => Tab::make('Rejected')
                ->modifyQueryUsing(fn (Builder $q) => $q->where('status', 'rejected')),
        ];
    }
}
