<?php

namespace App\Http\Resources\Company;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class CompanyResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if the request is for a collection
        $isCollection = $request->is('api/companies');

        $data = [
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'country' => $this->country,
            'industry' => $this->industry,
            'phone' => $this->phone,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];

        // Exclude fields if it's a collection request
        if ($isCollection) {
            unset($data['email']); // Exclude email field
            unset($data['createdAt']); // Exclude created_at field
            unset($data['updatedAt']); // Exclude updated_at field
        }

        return $data;
    }
}
