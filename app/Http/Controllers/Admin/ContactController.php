<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Contact;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::orderBy('created_at', 'desc')->paginate(30);

        return view('admin.contacts.index', ['contacts' => $contacts]);
    }

    public function show(Contact $contact)
    {
        return view('admin.contacts.show', ['contact' => $contact]);
    }

    public function updateStatus(Request $request, Contact $contact)
    {
        $contact->update(['status' => $request->status]);

        return redirect()->route('admin.contacts.index', ['contact' => $contact])->with('status', 'お問い合わせのステータスを更新しました');
    }
}
