<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Illuminate\Http\Resources\Json\ResourceCollection;
use Illuminate\Support\Facades\Auth;
use App\Models\Book;

class BorrowBookResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        $book = $this->resource;
        $bookDetails = Book::find($book->book_id);
        return [
            'id' => $this->id,
            'book_id' => $this->book_id,
            'user_id' => $this->user_id,
            'borrowed_at' => $this->created_at,
            'returned_at' => $this->updated_at,
            'book_details' => [
                'title' => $bookDetails ? $bookDetails->title : null,
                'author' => $bookDetails ? $bookDetails->author : null,
                'category' => $bookDetails ? $bookDetails->category : null,
            ],
        ];
        //return parent::toArray($request);
    }
}
