<?php

namespace App\Http\Controllers\API;

use App\Models\Book;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\BookResource;
use Illuminate\Support\Facades\Validator;

class BookController extends Controller
{
    public function index()
    {
        $books = Book::get();
        if($books->count() > 0)
        {
            return BookResource::collection($books);
        } else {
            return response()->json(['message' => 'No book available'], 200);
        }
    }
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(),[
                    'title' => 'required|string|max:255',
                    'author' => 'required|string|max:255',
                    'category' => 'required|string|max:255',
        
                ]);
        if($validator->fails())
        {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }

        //create a new book record
        $book = Book::create([
            'title' => $request->title,
            'author' => $request->author,
            'category' => $request->category,
        ]);

        // Return a success response with the created book data
        return response()->json([
            'message' => 'Book Created Successfully',
            'data' => new BookResource ($book)
        ],201);
    }
    public function show($id)
    {
        $book = Book::findOrFail($id);
        return response()->json([
            'message' => 'Book retrieved successfully',
            'data' => new BookResource($book)
        ], 200);

    }
    public function update()
    {
        $validator = Validator::make(request()->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);
        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->messages(),
            ], 422);
        }
        //update book record
        $book = Book::findOrFail(request()->id);
        $book->update([
            'title' => request()->title,
            'author' => request()->author,
            'category' => request()->category,
        ]);
        // Return a success response with the updated book data
        return response()->json([
            'message' => 'Book updated successfully',
            'data' => new BookResource($book)
        ], 200);

    }
    public function destroy()
    {
        $book = Book::findOrFail(request()->id);
        $book->delete();
        return response()->json([
            'message' => 'Book deleted successfully',
        ], 200);

}
}