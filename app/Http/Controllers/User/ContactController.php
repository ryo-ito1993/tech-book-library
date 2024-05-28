<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Requests\User\StoreContactRequest;
use Illuminate\View\View;
use App\Models\Contact;
use App\Mail\UserContactMail;
use App\Mail\AdminContactMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Http\RedirectResponse;

class ContactController extends Controller
{
    public function create(): View
    {
        return view('user.contacts.create');
    }

    public function confirm(StoreContactRequest $request): View
    {
        $validated = $request->validated();

        return view('user.contacts.confirm', ['contact' => $validated]);
    }

    // 送信処理
    public function store(StoreContactRequest $request): View
    {
        $validated = $request->validated();
        $contact = Contact::create($validated);

        // 送信者にメールを送信
        Mail::to($contact->email)->send(new UserContactMail($contact));

        // 管理者にメールを送信
        Mail::to(config('mail.from.address'))->send(new AdminContactMail($contact));

        return view('user.contacts.complete');
    }

    public function complete(): View
    {
        return view('user.contacts.complete');
    }

    public function back(): RedirectResponse
    {
        return redirect()->route('user.contacts.create')->withInput();
    }
}
