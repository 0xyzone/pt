<?php

namespace App\Filament\Maidan\Resources\Tournaments\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Hidden;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Components\Group;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class TournamentForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                Hidden::make('user_id')
                    ->default(Auth::id()),

                Grid::make(3)
                    ->columnSpanFull() // Makes the grid take the full width of the form
                    ->schema([
                        // ----------------------------------------------------
                        // MAIN COLUMN (Spans 2 out of 3 columns on desktop)
                        // ----------------------------------------------------
                        Group::make()
                            ->columnSpan(['default' => 3, 'lg' => 2])
                            ->schema([
                                Section::make('Tournament Details')
                                    ->description('Enter the core information and overview for the tournament.')
                                    ->icon('heroicon-o-information-circle')
                                    ->schema([
                                        TextInput::make('name')
                                            ->label('Tournament Name')
                                            ->required()
                                            ->maxLength(255)
                                            ->placeholder('e.g., Summer Championship 2026'),

                                        Textarea::make('description')
                                            ->columnSpanFull()
                                            ->autosize()
                                            ->rows(4)
                                            ->placeholder('Provide a brief description of the tournament.'),
                                    ]),

                                Section::make('Media & Assets')
                                    ->visibleOn(['create', 'edit']) // Only show on create/edit pages, hide on index
                                    ->description('Upload the branding visuals for the tournament.')
                                    ->icon('heroicon-o-photo')
                                    ->collapsible() // Allows collapsing to save space
                                    ->collapsed(true) // Start collapsed by default
                                    ->columns(2)    // Places the uploads side-by-side
                                    ->schema([
                                        FileUpload::make('logo_image')
                                            ->label('Tournament Logo')
                                            ->image()
                                            ->disk('public')
                                            ->directory('tournament-logos')
                                            ->imageAspectRatio('1:1')
                                            ->panelLayout('integrated')
                                            ->panelAspectRatio('1:1')
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions(['1:1'])
                                            ->automaticallyOpenImageEditorForAspectRatio()
                                            ->maxSize(800 * 1024)
                                            ->rules([Rule::dimensions()->ratio(1 / 1)])
                                            ->validationMessages([
                                                'dimensions' => 'The logo must have a square aspect ratio of 1:1 (e.g. 512x512 pixels).',
                                                'max' => 'The logo file size must not exceed 800MB.',
                                            ]),

                                        FileUpload::make('banner_image')
                                            ->label('Tournament Banner')
                                            ->image()
                                            ->disk('public')
                                            ->directory('tournament-banners')
                                            ->imageAspectRatio('16:9')
                                            ->panelLayout('integrated')
                                            ->panelAspectRatio('16:9')
                                            ->imageEditor()
                                            ->imageEditorAspectRatioOptions(['16:9'])
                                            ->automaticallyOpenImageEditorForAspectRatio()
                                            ->maxSize(800 * 1024)
                                            ->rules([Rule::dimensions()->ratio(16 / 9)])
                                            ->validationMessages([
                                                'dimensions' => 'The banner must have a landscape aspect ratio of 16:9 (e.g. 1920x1080 pixels).',
                                                'max' => 'The banner file size must not exceed 800MB.',
                                            ]),
                                    ]),
                            ]),

                        // ----------------------------------------------------
                        // SIDEBAR COLUMN (Spans 1 out of 3 columns on desktop)
                        // ----------------------------------------------------
                        Group::make()
                            ->columnSpan(['default' => 3, 'lg' => 1])
                            ->schema([
                                Section::make('Status')
                                    ->visibleOn(['create', 'edit']) // Only show on create/edit pages, hide on index
                                    ->icon('heroicon-o-check-badge')
                                    ->collapsible() // Allows collapsing to save space
                                    ->collapsed(true) // Start collapsed by default
                                    ->schema([
                                        Select::make('status')
                                            ->options([
                                                'upcoming' => 'Upcoming',
                                                'ongoing' => 'Ongoing',
                                                'completed' => 'Completed',
                                            ])
                                            ->default('upcoming')
                                            ->required()
                                            ->native(false), // Forces the beautiful custom Filament dropdown instead of browser native
                                    ]),

                                Section::make('Schedule & Location')
                                    ->icon('heroicon-o-calendar')
                                    ->schema([
                                        Grid::make(2)
                                            ->schema([
                                                DatePicker::make('start_date')
                                                    ->native(false),
                                                DatePicker::make('end_date')
                                                    ->native(false),
                                            ]),

                                        TextInput::make('location')
                                            ->prefixIcon('heroicon-o-map-pin')
                                            ->placeholder('e.g., Online, Region, or Venue'),
                                    ]),

                                Section::make('Contact & Social')
                                    ->visibleOn(['create', 'edit']) // Only show on create/edit pages, hide on index
                                    ->icon('heroicon-o-globe-alt')
                                    ->collapsible() // Allows collapsing to save space
                                    ->collapsed(true) // Start collapsed by default
                                    ->schema([
                                        TextInput::make('contact_email')
                                            ->email()
                                            ->prefixIcon('heroicon-o-envelope')
                                            ->placeholder('admin@example.com'),

                                        TextInput::make('discord_link')
                                            ->url()
                                            ->prefixIcon('heroicon-o-chat-bubble-left-right')
                                            ->placeholder('https://discord.gg/...'),
                                    ]),
                            ]),
                    ]),
            ]);
    }
}