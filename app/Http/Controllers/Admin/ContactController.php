<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Services\ContactService;

class ContactController extends Controller
{
    public function __construct(
        protected ContactService $contactService
    ) {
    }

    public function index(Request $request): View
    {
        $searchInput = [
            'contactName' => $request->input('contactName'),
            'email' => $request->input('email'),
            'status' => $request->input('status'),
        ];
        $contacts = $this->contactService->search($searchInput)->orderBy('created_at', 'desc')->paginate(30);

        return view('admin.contacts.index', ['contacts' => $contacts]);
    }

    public function show(Contact $contact): View
    {
        return view('admin.contacts.show', ['contact' => $contact]);
    }

    public function updateStatus(Request $request, Contact $contact): RedirectResponse
    {
        $contact->update(['status' => $request->status]);

        return redirect()->back()->with('status', 'お問い合わせのステータスを更新しました');
    }
}
