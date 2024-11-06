<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class UserResource extends JsonResource
{

    public function __construct($resource, $description = null)
    {
        parent::__construct($resource);
        $this->description = $description;
    }

    public function toArray(Request $request): array
    {
        return [
            "id" => $this->id,
            "fullName" => $this->name,
            "image" => $this->image ? config('app.url') . '/storage/' . $this->image : null,
            "description" => $this->description ? [
                'short' => $this->description->short_description,
                'long' => $this->description->long_description,
            ] : [
                'short' => null,
                'long' => null,
            ],
        ];
    }
}
