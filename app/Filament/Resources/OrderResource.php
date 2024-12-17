<?php
namespace App\Filament\Resources;

use App\Filament\Resources\OrderResource\Pages;
use App\Models\Order;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;
use Filament\Tables\Actions\EditAction;
use Filament\Tables\Actions\DeleteBulkAction;
use Filament\Tables\Actions\BulkActionGroup;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static ?string $navigationIcon = 'heroicon-o-shopping-bag';

    // The form for creating and editing orders
    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                // Customer selection
                Forms\Components\Select::make('customer_id')
                    ->label('Customer')
                    ->relationship('customer', 'first_name')
                    ->required(),

                // Product selection
                Forms\Components\Select::make('product_id')
                    ->label('Product')
                    ->relationship('product', 'name')
                    ->required(),

                // Quantity input
                Forms\Components\TextInput::make('quantity')
                    ->label('Quantity')
                    ->numeric()
                    ->minValue(1)
                    ->required(),

                // Total price calculation
                Forms\Components\TextInput::make('total_price')
                    ->label('Total Price')
                    ->numeric()
                    ->required()
                    // ->disabled() // Disable editing total price, it will be calculated
                    ->default(fn ($get) => $get('quantity') * $get('product.price')),

                // Order status (e.g., pending, completed, canceled)
                Forms\Components\Select::make('status')
                    ->label('Status')
                    ->options([
                        'pending' => 'Pending',
                        'completed' => 'Completed',
                        'canceled' => 'Canceled',
                    ])
                    ->default('pending')
                    ->required(),
            ]);
    }

    // The table for listing orders
    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                // Customer name
                Tables\Columns\TextColumn::make('customer.first_name')
                    ->label('Customer')
                    ->sortable()
                    ->searchable(),

                // Product name
                Tables\Columns\TextColumn::make('product.name')
                    ->label('Product')
                    ->sortable()
                    ->searchable(),

                // Quantity ordered
                Tables\Columns\TextColumn::make('quantity')
                    ->label('Quantity')
                    ->sortable(),

                // Total price of the order
                Tables\Columns\TextColumn::make('total_price')
                    ->label('Total Price')
                    ->sortable(),

                    Tables\Columns\BadgeColumn::make('status')
                    ->label('Status')
                    ->colors([
                        'pending' => 'pending',
                        'completed' => 'completed',
                    ]),
            ])
            ->filters([
                // You can add filters here if needed
            ])
            ->actions([
                // Edit action to modify order details
                EditAction::make(),
            ])
            ->bulkActions([
                BulkActionGroup::make([
                    // Bulk delete action
                    DeleteBulkAction::make(),
                ]),
            ]);
    }

    // Relations (optional if you need to load related models)
    public static function getRelations(): array
    {
        return [
            // Example: You could add a relation manager here if needed
        ];
    }

    // Pages (defining routes for the pages in the resource)
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListOrders::route('/'),
            'create' => Pages\CreateOrder::route('/create'),
            'edit' => Pages\EditOrder::route('/{record}/edit'),
        ];
    }
}
