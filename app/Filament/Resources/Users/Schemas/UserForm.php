<?php

namespace App\Filament\Resources\Users\Schemas;

use Filament\Forms\Components\DatePicker;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->components([
                TextInput::make('first_name')
                    ->required(),
                TextInput::make('last_name')
                    ->required(),
                TextInput::make('phone')
                    ->tel(),
                TextInput::make('email')
                    ->label('Email address')
                    ->email(),
                DatePicker::make('birth_date'),
                TextInput::make('address'),
                TextInput::make('photo'),
                TextInput::make('password')
                    ->password()
                    ->required(),
                Select::make('status')
                    ->options([
                        'PENDING' => 'P e n d i n g',
                        'ACTIVE' => 'A c t i v e',
                        'INACTIVE' => 'I n a c t i v e',
                        'SUSPENDED' => 'S u s p e n d e d',
                    ])
                    ->default('PENDING')
                    ->required(),
                DateTimePicker::make('email_verified_at'),
            ]);
    }
}
