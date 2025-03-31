<?php

namespace App\Http\Resources\Inquiry;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\ResourceCollection;

class InquiryCollection extends ResourceCollection
{
    /**
     * Transform the resource collection into an array.
     *
     * @return array<int|string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'data' => $this->collection,
            'meta' => [
                'currentPage' => $this->currentPage(),
                'lastPage' => $this->lastPage(),
                'perPage' => $this->perPage(),
                'total' => $this->total(),
            ],
        ];
    }
}
