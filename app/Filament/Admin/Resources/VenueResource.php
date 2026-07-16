<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\VenueResource\Pages;
use App\Filament\Admin\Resources\VenueResource\RelationManagers;
use App\Models\Venue;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\SoftDeletingScope;

class VenueResource extends Resource
{
    protected static ?string $model = Venue::class;

    protected static ?string $navigationIcon = 'heroicon-o-home-modern';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Section::make('Venue Details')
                    ->description('Provide the core information about this venue.')
                    ->schema([
                        Forms\Components\TextInput::make('name')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., Grand Ballroom, Sunset Hall'),

                        Forms\Components\TextInput::make('location')
                            ->required()
                            ->maxLength(255)
                            ->placeholder('e.g., 123 Main St, New York'),

                        Forms\Components\TextInput::make('capacity')
                            ->required()
                            ->numeric()
                            ->minValue(1)
                            ->placeholder('e.g., 150')
                            ->label('Capacity (people)'),

                        Forms\Components\TextInput::make('price_per_hour')
                            ->required()
                            ->numeric()
                            ->minValue(0)
                            ->prefix('$')
                            ->placeholder('e.g., 75.00')
                            ->label('Price per Hour'),

                        Forms\Components\Textarea::make('description')
                            ->required()
                            ->columnSpanFull()
                            ->rows(4)
                            ->placeholder('Describe the venue amenities, rules, and layout...'),
                    ])->columns(2),

                Forms\Components\Section::make('Venue Media')
                    ->description('Upload images of the venue to showcase to users.')
                    ->schema([
                        Forms\Components\FileUpload::make('image_path')
                            ->image()
                            ->directory('venues')
                            ->imageResizeMode('cover')
                            ->imageCropAspectRatio('16:9')
                            ->imageResizeTargetWidth('1280')
                            ->imageResizeTargetHeight('720')
                            ->columnSpanFull()
                            ->label('Cover Photo'),
                    ]),

                Forms\Components\Section::make('Availability Calendar')
                    ->description('Click a date to manually block or unblock availability for this venue.')
                    ->schema([
                        Forms\Components\View::make('components.venue-mini-calendar')
                            ->viewData(['isAdmin' => true])
                    ])
                    ->hiddenOn('create'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\ImageColumn::make('image_path')
                    ->label('Photo')
                    ->square(),
                Tables\Columns\TextColumn::make('name')
                    ->searchable()
                    ->sortable()
                    ->weight('bold'),
                Tables\Columns\TextColumn::make('location')
                    ->searchable()
                    ->sortable()
                    ->color('gray'),
                Tables\Columns\TextColumn::make('capacity')
                    ->numeric()
                    ->sortable()
                    ->label('Capacity')
                    ->alignCenter(),
                Tables\Columns\TextColumn::make('price_per_hour')
                    ->money('USD')
                    ->sortable()
                    ->label('Rate/Hr'),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
                Tables\Columns\TextColumn::make('updated_at')
                    ->dateTime()
                    ->sortable()
                    ->toggleable(isToggledHiddenByDefault: true),
            ])
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ]);
    }

    public static function getRelations(): array
    {
        return [
            //
        ];
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListVenues::route('/'),
            'create' => Pages\CreateVenue::route('/create'),
            'edit' => Pages\EditVenue::route('/{record}/edit'),
        ];
    }
}
