<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductRecommendationResource\Pages;
use App\Models\ProductRecommendation;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;

class ProductRecommendationResource extends Resource
{
    protected static ?string $model = ProductRecommendation::class;

    protected static ?string $navigationIcon = 'heroicon-o-sparkles';

    protected static ?string $navigationLabel = 'AI Recommendations';

    protected static ?int $navigationSort = 3;

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\TextInput::make('session_id')
                    ->label('Session ID')
                    ->disabled(),
                Forms\Components\Select::make('user_id')
                    ->label('User')
                    ->relationship('user', 'name')
                    ->disabled(),
                Forms\Components\Toggle::make('is_ai_generated')
                    ->label('AI Generated')
                    ->disabled(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('id')
                    ->label('ID')
                    ->sortable(),
                Tables\Columns\IconColumn::make('is_ai_generated')
                    ->label('AI')
                    ->boolean(),
                Tables\Columns\TextColumn::make('viewed_product_ids')
                    ->label('Viewed')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) : 0),
                Tables\Columns\TextColumn::make('recommended_product_ids')
                    ->label('Recommended')
                    ->formatStateUsing(fn ($state) => is_array($state) ? count($state) : 0),
                Tables\Columns\TextColumn::make('user.name')
                    ->label('User')
                    ->default('Guest'),
                Tables\Columns\TextColumn::make('created_at')
                    ->label('Created')
                    ->dateTime()
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => Pages\ListProductRecommendations::route('/'),
        ];
    }
}
