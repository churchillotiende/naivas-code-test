<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CustomerResource\Pages;
use App\Models\Customer;
use App\Services\RecommendationService;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Filament\Tables\Actions\Action;

class CustomerResource extends Resource
{
    protected static ?string $model = Customer::class;

    protected static ?string $navigationIcon = 'heroicon-o-user';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('first_name')
                    ->label('First Name')
                    ->required(),

                Forms\Components\TextInput::make('last_name')
                    ->label('Last Name')
                    ->required(),

                Forms\Components\TextInput::make('email')
                    ->label('Email')
                    ->email()
                    ->required(),

                Forms\Components\TextInput::make('phone')
                    ->label('Phone')
                    ->tel(),

                Forms\Components\TextInput::make('city')
                    ->label('City'),

                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'active' => 'Active',
                        'inactive' => 'Inactive',
                    ])
                    ->default('active'),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('first_name')
                    ->label('First Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('last_name')
                    ->label('Last Name')
                    ->sortable()
                    ->searchable(),

                Tables\Columns\TextColumn::make('email')
                    ->label('Email'),

                Tables\Columns\TextColumn::make('status')
                    ->label('Status')
                    ->badge()
                    ->colors([
                        'active' => 'success',
                        'inactive' => 'danger',
                    ]),
            ])
            ->actions([
                Tables\Actions\EditAction::make(),

                // Add a custom action to show recommendations
                Action::make('recommendations')
                    ->label('View Recommendations')
                    ->icon('heroicon-o-light-bulb')
                    ->action(fn ($record) => self::showRecommendations($record->id))
                    ->modalHeading('Recommended Products')
                    ->modalContent(function ($record) {
                        $service = app(RecommendationService::class);
                        $recommendations = $service->suggestProductsForCustomer($record->id);
                    
                        return view('filament.resources.customers.recommendations', [
                            'products' => $recommendations,
                        ]);
                    })                    
            ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListCustomers::route('/'),
            'create' => Pages\CreateCustomer::route('/create'),
            'edit' => Pages\EditCustomer::route('/{record}/edit'),
        ];
    }

    /**
     * Helper method to fetch and display recommendations.
     */
    private static function showRecommendations($customerId)
    {
        $service = app(RecommendationService::class);
        return $service->suggestProductsForCustomer($customerId);
    }
}
