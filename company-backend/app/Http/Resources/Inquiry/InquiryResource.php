<?php

namespace App\Http\Resources\Inquiry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InquiryResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Check if the request is for a collection
        $isCollection = $request->is('api/inquiries');
        $data=[
            'id' => $this->id,
            'name' => $this->name,
            'email' => $this->email,
            'companyId' => $this->company_id,
            'phone' => $this->phone,
            'createdAt' => $this->created_at->toDateTimeString(),
            'updatedAt' => $this->updated_at->toDateTimeString(),
        ];

        // Exclude fields if it's a collection request
        if ($isCollection) {
            unset($data['updatedAt']); // Exclude updated_at field
        }

        return $data;


    }
}
