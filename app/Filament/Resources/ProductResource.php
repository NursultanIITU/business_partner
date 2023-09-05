<?php

namespace App\Filament\Resources;

use App\Filament\Resources\ProductResource\Pages;
use App\Filament\Resources\ProductResource\RelationManagers;
use App\Models\Category;
use App\Models\Product;
use Filament\Forms\Components\Card;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\ToggleColumn;
use Filament\Tables\Table;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\Toggle;
use Filament\Forms\Components\SpatieMediaLibraryFileUpload;
use Filament\Tables\Columns\SpatieMediaLibraryImageColumn;
use Filament\Forms\Components\Select;
use Illuminate\Database\Query\Builder;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static ?string $navigationIcon = 'heroicon-o-rectangle-stack';

    protected static ?string $navigationGroup = 'Business Partner';

    protected static ?int $navigationSort = 1;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Card::make()
                    ->schema([
                        TextInput::make('name')
                            ->maxLength(255)
                            ->label(__('product.resource.name'))
                            ->required(),
                        Select::make('category_id')
                            ->label(__('product.resource.category'))
                            ->options(flattenCategories(Category::get()->toTree()))
                            ->searchable()
                            ->required(),
                        TextInput::make('price')
                            ->integer()
                            ->label(__('product.resource.price'))
                            ->required(),
                        RichEditor::make('description')
                            ->label(__('product.resource.description'))
                            ->required(),
                        SpatieMediaLibraryFileUpload::make('images')
                            ->multiple()
                            ->enableReordering()
                            ->label(__('product.resource.images'))
                            ->required(),
                        Toggle::make('is_available')
                            ->label(__('product.resource.is_available'))
                    ]),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('id')->sortable(),
                TextColumn::make('name')
                    ->label(__('product.resource.name'))
                    ->sortable()
                    ->searchable(),
                TextColumn::make('category.name')
                    ->label(__('product.resource.category')),
                TextColumn::make('price')
                    ->sortable()
                    ->money('rub')
                    ->label(__('product.resource.price')),
                SpatieMediaLibraryImageColumn::make('images')
                    ->label(__('product.resource.images'))
                    ->circular(),
                ToggleColumn::make('is_available')
                    ->label(__('product.resource.is_available')),
            ])
            ->defaultSort('price', 'desc')
            ->filters([

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
            'index' => Pages\ListProducts::route('/'),
            'create' => Pages\CreateProduct::route('/create'),
            'edit' => Pages\EditProduct::route('/{record}/edit'),
        ];
    }

    public static function getLabel(): ?string
    {
        return __('product.resource.label');
    }

    public static function getPluralLabel(): ?string
    {
        return __('product.resource.plural_label');
    }
}
