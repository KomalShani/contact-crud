<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class ContactController extends Controller
{
    public function index()
    {
        $contacts = Contact::all()->sortByDesc('created_at');
        return view('contacts.index', compact('contacts'));
    }

    // Show the form to create a new contact
    public function create()
    {
        return view('contacts.create');
    }

    private function validate(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/|max:15',
        ]);
    }

    // Store a newly created contact
    public function store(Request $request)
    {
        $this->validate($request);

        Contact::create($request->all());
        return redirect()->route('contacts.index')->with('success', 'Contact created successfully.');
    }

    // Show the form to edit a contact
    public function edit($id)
    {
        $contact = Contact::findOrFail($id);
        return view('contacts.edit', compact('contact'));
    }

    // Update an existing contact
    public function update(Request $request, $id)
    {
        $this->validate($request);

        $contact = Contact::findOrFail($id);
        $contact->update($request->all());
        return redirect()->route('contacts.index')->with('success', 'Contact updated successfully.');
    }

    // Delete a contact
    public function destroy($id)
    {
        $contact = Contact::findOrFail($id);
        $contact->delete();
        return redirect()->route('contacts.index')->with('success', 'Contact deleted successfully.');
    }

    public function import()
    {
        $contacts = Contact::all();
        return view('contacts.add', compact('contacts'));
    }

    public function importXML(Request $request)
    {
        $request->validate([
            'contacts_file' => 'required|file|mimes:xml|max:2048', // XML only, with a max size of 2MB
        ]);

        $xmlString = file_get_contents($request->file('contacts_file'));
        try {
            $xml = simplexml_load_string($xmlString);
            $contacts = json_decode(json_encode($xml), true);

            if (!isset($contacts['contact']) || empty($contacts['contact'])) {
                return back()->withErrors(['contacts_file' => 'The XML file does not contain valid contact entries.']);
            }

            // Validate each contact record in the XML
            foreach ($contacts['contact'] as $contact) {
                $validator = Validator::make($contact, [
                    'name' => 'required|string|max:255',
                    'lastName' => 'required|string|max:255',
                    'phone' => 'required|regex:/^\+?[1-9]\d{1,14}$/|max:15',
                ]);

                if ($validator->fails()) {
                    return back()->withErrors($validator)->withInput();
                }

                // Store the contact if valid
                Contact::create([
                    'name' => $contact['name'],
                    'last_name' => $contact['lastName'],
                    'phone' => $contact['phone'],
                ]);
            }

            return redirect()->route('contacts.index')->with('success', 'Contacts imported successfully.');
        } catch (\Exception $e) {
            return back()->withErrors(['contacts_file' => 'The uploaded XML file is invalid or corrupted.']);
        }
    }
}
