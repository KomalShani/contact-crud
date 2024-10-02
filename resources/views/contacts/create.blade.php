@extends('layouts.app')

@section('title', 'Create Contact')

@section('content')
    <h1>Create Contact</h1>

    <form action="{{ route('contacts.store') }}" method="POST" class="mt-3">
        @csrf
        <div class="form-group">
            <label for="name">Name:</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="last_name">Last Name:</label>
            <input type="text" name="last_name" class="form-control" value="{{ old('last_name') }}" required>
            @error('last_name')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <div class="form-group">
            <label for="phone">Phone:</label>
            <input type="text" name="phone" class="form-control" value="{{ old('phone') }}" required>
            @error('phone')
                <div class="text-danger">{{ $message }}</div>
            @enderror
        </div>

        <button type="submit" class="btn btn-success">Create</button>
        <a href="{{ route('contacts.index') }}" class="btn btn-secondary">Back</a>
    </form>
@endsection
