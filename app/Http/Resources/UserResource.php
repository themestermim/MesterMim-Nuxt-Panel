<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function __construct($resource, $descriptions = null)
    {
        parent::__construct($resource);
        $this->descriptions = $descriptions;
    }

    public function toArray(Request $request): array
    {
        $descriptionFa = $this->descriptions->where('lang', 'fa')->first();
        $descriptionEn = $this->descriptions->where('lang', 'en')->first();
        return [
            "id" => $this->id,
            "fullName" => $this->name,
            "image" => $this->image ? config('app.url') . '/storage/' . $this->image : null,
            "description_fa" => $descriptionFa ? [
                'short' => $descriptionFa->short_description,
                'long' => $descriptionFa->long_description,
            ] : [
                'short' => null,
                'long' => null,
            ],
            "description_en" => $descriptionEn ? [
                'short' => $descriptionEn->short_description,
                'long' => $descriptionEn->long_description,
            ] : [
                'short' => null,
                'long' => null,
            ],
        ];
    }
}
