<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Book;
use Illuminate\View\View;

class NotificationBookController extends Controller
{
    public function index(): View
    {
        $user = auth()->user();
        $notificationBookIds = $user->notificationBooks()->pluck('book_id');
        $books = Book::whereIn('id', $notificationBookIds)->with('authors')->latest()->paginate(12);

        return view('user.notification_books.index', ['books' => $books, 'user' => $user]);
    }
}
