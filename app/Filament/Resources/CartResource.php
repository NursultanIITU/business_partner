<?php

namespace App\Filament\Resources;

use App\Filament\Resources\CartResource\Pages;
use App\Filament\Resources\CartResource\RelationManagers;
use App\Models\Cart;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Grid;
use Filament\Forms\Components\TextInput;

class CartResource extends Resource
{
    protected static ?string $model = Cart::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Business Partner';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Grid::make(2)
                    ->schema([
                        Select::make('product_id')
                            ->relationship(name: 'product', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->label(__('cart.resource.product'))
                            ->required(),
                        Select::make('user_id')
                            ->relationship(name: 'user', titleAttribute: 'name')
                            ->searchable()
                            ->preload()
                            ->required()
                            ->label(__('cart.resource.user')),
                        TextInput::make('quantity')
                            ->numeric()
                            ->required()
                            ->label(__('cart.resource.quantity')),
                        TextInput::make('amount')
                            ->numeric()
                            ->required()
                            ->label(__('cart.resource.amount')),
                        ])
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('product.name')
                    ->label(__('cart.resource.product')),
                TextColumn::make('user.name')
                    ->label(__('cart.resource.user')),
                TextColumn::make('quantity')
                    ->label(__('cart.resource.quantity')),
                TextColumn::make('amount')
                    ->money('rub')
                    ->label(__('cart.resource.amount'))
                    ->summarize(Tables\Columns\Summarizers\Sum::make()),
            ])
            ->defaultSort('created_at', 'desc')
            ->defaultGroup('product.name')
            ->filters([
                //
            ])
            ->actions([
                Tables\Actions\EditAction::make(),
                Tables\Actions\DeleteAction::make(),
            ])
            ->bulkActions([
                Tables\Actions\BulkActionGroup::make([
                    Tables\Actions\DeleteBulkAction::make(),
                ]),
            ])
            ->emptyStateActions([
                Tables\Actions\CreateAction::make(),
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
            'index' => Pages\ListCarts::route('/'),
            'create' => Pages\CreateCart::route('/create'),
            'edit' => Pages\EditCart::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return __('cart.resource.label');
    }

    public static function getPluralLabel(): ?string
    {
        return __('cart.resource.plural_label');
    }
}
