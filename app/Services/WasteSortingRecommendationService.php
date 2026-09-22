<?php
namespace App\Services;
use App\Models\WasteCategory;
class WasteSortingRecommendationService { public function for(WasteCategory $category): array { return ['instruction'=>$category->sorting_instruction ?: 'Pisahkan material, pastikan bersih dan kering sebelum disetor.','route'=>$category->processing_route ?: 'Rute pengolahan ditentukan oleh mitra.']; } }
