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
        $bookDetails = Book::find($this->book_id);

        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'book_id' => $this->book_id,
            'borrow_date' => $this->borrow_date,
            'due_date' => $this->due_date,
            'return_date' => $this->return_date,
            'status' => $this->status,
            'book_details' => $bookDetails ? [
                'title' => $bookDetails->title,
                'author' => $bookDetails->author,
                'category' => $bookDetails->category,
            ] : null,
        ];
        foreach ($books as $book) {
            Book::create($book);
        }
    }
}
