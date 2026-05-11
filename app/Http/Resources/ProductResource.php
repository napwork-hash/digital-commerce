<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ProductResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'category' => new CategoryResource($this->whenLoaded('category')),
            'category_id' => $this->category_id,

            'name' => $this->name,
            'slug' => $this->slug,
            'short_description' => $this->short_description,
            'description' => $this->description,

            'price' => [
                'amount' => (float) $this->price,
                'formatted' => 'Rp ' . number_format((float) $this->price, 0, ',', '.'),
            ],
            'compare_at_price' => $this->compare_at_price ? [
                'amount' => (float) $this->compare_at_price,
                'formatted' => 'Rp ' . number_format((float) $this->compare_at_price, 0, ',', '.'),
            ] : null,

            'preview_image' => $this->preview_image,
            'gallery' => $this->gallery ?? [],

            /**
             * Security note:
             * Jangan expose digital_file_path ke public response.
             * File download nanti harus lewat endpoint protected.
             */
            'file_type' => $this->file_type,
            'file_size' => $this->file_size,
            'file_size_label' => $this->formatFileSize($this->file_size),

            'is_active' => $this->is_active,
            'is_featured' => $this->is_featured,

            'created_at' => $this->created_at?->toISOString(),
            'updated_at' => $this->updated_at?->toISOString(),
        ];
    }

    private function formatFileSize(?int $bytes): ?string
    {
        if (!$bytes) {
            return null;
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $bytes;
        $unitIndex = 0;

        while ($size >= 1024 && $unitIndex < count($units) - 1) {
            $size /= 1024;
            $unitIndex++;
        }

        return round($size, 2) . ' ' . $units[$unitIndex];
    }
}
