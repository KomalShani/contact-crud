@extends('layouts.app')

@section('title', 'Contacts List')

@section('content')
    <h1>Contacts</h1>

    <a href="{{ route('contacts.create') }}" class="btn btn-primary mb-3">Create New Contact</a>

    <form action="{{ route('contacts.import') }}" method="POST" enctype="multipart/form-data" class="mb-3">
        @csrf
        <div class="form-group">
            <input type="file" name="contacts_file" accept=".xml" class="form-control">
            @error('contacts_file')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>
        <button type="submit" class="btn btn-info">Import Contacts</button>
    </form>

    <table class="table table-striped table-bordered">
        <thead>
            <tr>
                <th>Name</th>
                <th>Last Name</th>
                <th>Phone</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach ($contacts as $contact)
                <tr>
                    <td>{{ $contact->name }}</td>
                    <td>{{ $contact->last_name }}</td>
                    <td>{{ $contact->phone }}</td>
                    <td>
                        <a href="{{ route('contacts.edit', $contact->id) }}" class="btn btn-warning btn-sm">Edit</a>
                        <form action="{{ route('contacts.destroy', $contact->id) }}" method="POST" style="display:inline;">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm">Delete</button>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
