<?php

namespace App\Http\Controllers\API;

use Illuminate\Http\Request;
use App\Http\Requests;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator as ValidatorFacade;
use Illuminate\Support\Facades\URL;


class BorrowBookController extends Controller
{
    public function index()
    {
        // Fetch all borrowed books from the database
        $borrowedBooks = DB::table('borrows')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->select('borrows.*', 'books.title', 'books.author', 'books.category')
            ->where('borrows.user_id', Auth::id())
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function store(Request $request)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->messages(),
            ], 422);
        }

        // Create a new borrowed book record
        DB::table('borrows')->insert([
            'book_id' => $request->book_id,
            'user_id' => Auth::id(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Return a success response with the created borrowed book data
        return response()->json([
            'message' => 'Book borrowed successfully',
        ], 201);
    }
    public function show($id)
    {
        // Find the borrowed book by ID
        $borrowedBook = DB::table('borrows')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->select('borrows.*', 'books.title', 'books.author', 'books.category')
            ->where('borrows.id', $id)
            ->where('borrows.user_id', Auth::id())
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function update(Request $request, $id)
    {
        // Validate the request data
        $validator = Validator::make($request->all(), [
            'book_id' => 'required|exists:books,id',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'message' => 'Validation failed',
                'errors' => $validator->messages(),
            ], 422);
        }

        // Update the borrowed record
        DB::table('borrows')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->update([
                'book_id' => $request->book_id,
                'updated_at' => now(),
            ]);

        // Return a success response with the updated borrowed book data
        return response()->json([
            'message' => 'Borrowed book updated successfully',
        ], 200);
    }
    public function destroy($id)
    {
        // Delete the borrowed book record
        DB::table('borrows')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->delete();

        // Return a success response
        return response()->json([
            'message' => 'Borrowed book deleted successfully',
        ], 200);
    }
    public function returnBook($id)
    {
        // Find the borrowed book by ID
        $borrowedBook = DB::table('borrows')
            ->where('id', $id)
            ->where('user_id', Auth::id())
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Update the returned_at timestamp
        DB::table('borrows')
            ->where('id', $id)
            ->update([
                'returned_at' => now(),
            ]);

        // Return a success response
        return response()->json([
            'message' => 'Book returned successfully',
        ], 200);
    }
    public function getBorrows()
    {
        // Fetch all borrowed books from the database
        $borrowedBooks = DB::table('borrows')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->select('borrows.*', 'books.title', 'books.author', 'books.category')
            ->where('borrows.user_id', Auth::id())
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBook($id)
    {
        // Find the borrowed book by ID
        $borrowedBook = DB::table('borrows')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->select('borrows.*', 'books.title', 'books.author', 'books.category')
            ->where('borrows.id', $id)
            ->where('borrows.user_id', Auth::id())
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByUser($userId)
    {
        // Fetch all borrowed books for a specific user from the database
        $borrowedBooks = DB::table('borrows')
            ->join('books', 'borrows.book_id', '=', 'books.id')
            ->select('borrows.*', 'books.title', 'books.author', 'books.category')
            ->where('borrows.user_id', $userId)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);

    }
    public function getBorrowedBookByUser($userId)
    {
        // Find the borrowed book by ID for a specific user
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByBook($bookId)
    {
        // Fetch all borrowed books for a specific book from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.book_id', $bookId)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBookByBook($bookId)
    {
        // Find the borrowed book by ID for a specific book
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.book_id', $bookId)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByDate($date)
    {
        // Fetch all borrowed books for a specific date from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->whereDate('borrowed_books.created_at', $date)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBookByDate($date)
    {
        // Find the borrowed book by ID for a specific date
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->whereDate('borrowed_books.created_at', $date)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByStatus($status)
    {
        // Fetch all borrowed books for a specific status from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.status', $status)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBookByStatus($status)
    {
        // Find the borrowed book by ID for a specific status
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.status', $status)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByUserAndBook($userId, $bookId)
    {
        // Fetch all borrowed books for a specific user and book from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->where('borrowed_books.book_id', $bookId)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBookByUserAndBook($userId, $bookId)
    {
        // Find the borrowed book by ID for a specific user and book
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->where('borrowed_books.book_id', $bookId)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByUserAndDate($userId, $date)
    {
        // Fetch all borrowed books for a specific user and date from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->whereDate('borrowed_books.created_at', $date)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    public function getBorrowedBookByUserAndDate($userId, $date)
    {
        // Find the borrowed book by ID for a specific user and date
        $borrowedBook = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->whereDate('borrowed_books.created_at', $date)
            ->first();

        if (!$borrowedBook) {
            return response()->json(['message' => 'Borrowed book not found'], 404);
        }

        // Return a success response with the borrowed book data
        return response()->json([
            'message' => 'Borrowed book retrieved successfully',
            'data' => $borrowedBook
        ], 200);
    }
    public function getBorrowedBooksByUserAndStatus($userId, $status)
    {
        // Fetch all borrowed books for a specific user and status from the database
        $borrowedBooks = DB::table('borrowed_books')
            ->join('books', 'borrowed_books.book_id', '=', 'books.id')
            ->select('borrowed_books.*', 'books.title', 'books.author', 'books.category')
            ->where('borrowed_books.user_id', $userId)
            ->where('borrowedBooks.status', $status)
            ->get();

        // Return a success response with the list of borrowed books
        return response()->json([
            'message' => 'Borrowed books retrieved successfully',
            'data' => $borrowedBooks
        ], 200);
    }
    
}
