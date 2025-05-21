<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class BookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        // Return the book data in a structured format
        return [
            'Bookid' => $this->id,
            'Title' => $this->title,
            'Author' => $this->author,
            'Category' => $this->category,
        ];
    }
}

