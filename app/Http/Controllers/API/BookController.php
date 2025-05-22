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
        $books = Book::all();

        if ($books->isEmpty()) {
            return response()->json(['message' => 'No books available'], 200);
        }

        return BookResource::collection($books);
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->errors(),
            ], 422);
        }

        $book = Book::create($request->only(['title', 'author', 'category', 'description']));

        // Return a success response with the created book data
        return response()->json([
            'message' => 'Book created successfully',
            'data' => new BookResource($book),
        ], 201);
    }

    public function show($id)
    {
        $book = Book::findOrFail($id);

        return response()->json([
            'message' => 'Book retrieved successfully',
            'data' => new BookResource($book),
        ], 200);

    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'author' => 'required|string|max:255',
            'category' => 'required|string|max:255',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'All fields are mandatory',
                'error' => $validator->errors(),
            ], 422);
        }

        $book = Book::findOrFail($id);
        $book->update($request->only(['title', 'author', 'category']));

        return response()->json([
            'message' => 'Book updated successfully',
            'data' => new BookResource($book),
        ], 200);

    }

    public function destroy($id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return response()->json([
            'message' => 'Book deleted successfully',
        ], 200);
    }
}
