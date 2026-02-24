<?php

namespace App\Filament\Resources\Users;

use App\Filament\Resources\Users\Pages\CreateUser;
use App\Filament\Resources\Users\Pages\EditUser;
use App\Filament\Resources\Users\Pages\ListUsers;
use App\Models\User;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;

class UserResource extends Resource
{
    protected static ?string $model = User::class;

    protected static string|\BackedEnum|null $navigationIcon = 'heroicon-o-users';

    protected static ?string $navigationLabel = 'Jeunes';

    protected static ?string $modelLabel = 'Jeune';

    protected static ?string $pluralModelLabel = 'Jeunes';

    protected static ?int $navigationSort = 1;

    public static function form(Schema $form): Schema
    {
        return $form->components([

            Section::make('Identité')
                ->schema([
                    TextInput::make('first_name')
                        ->label('Prénom')
                        ->required()
                        ->maxLength(255),

                    TextInput::make('last_name')
                        ->label('Nom')
                        ->required()
                        ->maxLength(255),

                    DatePicker::make('birth_date')
                        ->label('Date de naissance')
                        ->nullable(),
                ])->columns(3),

            Section::make('Identifiants de connexion')
                ->description('Au moins un des deux est obligatoire.')
                ->schema([
                    TextInput::make('phone')
                        ->label('Numéro de téléphone')
                        ->tel()
                        ->unique(ignoreRecord: true)
                        ->nullable()
                        ->maxLength(20),

                    TextInput::make('email')
                        ->label('Email')
                        ->email()
                        ->unique(ignoreRecord: true)
                        ->nullable()
                        ->maxLength(255),
                ])->columns(2),

            Section::make('Statut du compte')
                ->schema([
                    Select::make('status')
                        ->label('Statut')
                        ->options([
                            'PENDING' => 'En attente',
                            'ACTIVE' => 'Actif',
                            'INACTIVE' => 'Inactif',
                            'SUSPENDED' => 'Suspendu',
                        ])
                        ->required()
                        ->default('PENDING'),
                ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('full_name')
                    ->label('Nom complet')
                    ->searchable(['first_name', 'last_name'])
                    ->sortable(['last_name']),

                TextColumn::make('phone')
                    ->label('Téléphone')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('email')
                    ->label('Email')
                    ->searchable()
                    ->placeholder('—'),

                TextColumn::make('status')
                    ->label('Statut')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'ACTIVE' => 'success',
                        'PENDING' => 'warning',
                        'INACTIVE' => 'gray',
                        'SUSPENDED' => 'danger',
                        default => 'gray',
                    }),

                TextColumn::make('created_at')
                    ->label('Créé le')
                    ->dateTime('d/m/Y')
                    ->sortable(),
            ])
            ->filters([
                SelectFilter::make('status')
                    ->label('Statut')
                    ->options([
                        'PENDING' => 'En attente',
                        'ACTIVE' => 'Actif',
                        'INACTIVE' => 'Inactif',
                        'SUSPENDED' => 'Suspendu',
                    ]),
            ])
            ->actions([
                EditAction::make(),
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
            'index' => ListUsers::route('/'),
            'create' => CreateUser::route('/create'),
            'edit' => EditUser::route('/{record}/edit'),
        ];
    }
}
