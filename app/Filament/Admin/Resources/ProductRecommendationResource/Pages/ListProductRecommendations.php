<?php

namespace App\Filament\Admin\Resources\ProductRecommendationResource\Pages;

use App\Filament\Admin\Resources\ProductRecommendationResource;
use Filament\Resources\Pages\ListRecords;

class ListProductRecommendations extends ListRecords
{
    protected static string $resource = ProductRecommendationResource::class;

    protected function getHeaderActions(): array
    {
        return [];
    }
}
