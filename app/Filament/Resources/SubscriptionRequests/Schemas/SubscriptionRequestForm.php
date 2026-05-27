<?php

namespace App\Filament\Resources\SubscriptionRequests\Schemas;

use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;

class SubscriptionRequestForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Section::make('Subscription Request Information')
                    ->columns(2)
                    ->schema([
                        Select::make('user_id')
                            ->label('User')
                            ->relationship('user', 'name')
                            ->disabled()
                            ->required(),
                        Select::make('plan_id')
                            ->label('Requested Plan')
                            ->relationship('plan', 'name')
                            ->disabled()
                            ->required(),
                        Select::make('status')
                            ->label('Request Status')
                            ->options([
                                'pending' => 'Pending',
                                'approved' => 'Approved',
                                'rejected' => 'Rejected',
                            ])
                            ->disabled()
                            ->required(),
                        Textarea::make('admin_notes')
                            ->label('Admin Notes / Reason')
                            ->placeholder('Add review notes here...')
                            ->rows(3),
                        FileUpload::make('screenshot_path')
                            ->label('Transaction Screenshot')
                            ->image()
                            ->disk('public')
                            ->disabled()
                            ->dehydrated(false)
                            ->openable()
                            ->columnSpanFull(),
                    ]),
            ]);
    }
}
