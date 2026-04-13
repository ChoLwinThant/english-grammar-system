@extends('layouts.app')

@section('content')
    <div class="card shadow-sm">
        <div class="card-body">
            <h2 class="mb-4">Edit Topic</h2>

            @if($errors->any())
                <div class="alert alert-danger">
                    <ul class="mb-0">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('admin.topics.update', $topic) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <label for="category_id" class="form-label">Category</label>
                    <select name="category_id" id="category_id" class="form-select">
                        <option value="">Select Category</option>
                        @foreach($categories as $category)
                            <option value="{{ $category->id }}" {{ old('category_id', $topic->category_id) == $category->id ? 'selected' : '' }}>
                                {{ $category->name }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="mb-3">
                    <label for="name" class="form-label">Topic Name</label>
                    <input type="text" name="name" id="name" class="form-control" value="{{ old('name', $topic->name) }}">
                </div>

                <button type="submit" class="btn btn-primary">Update</button>
                <a href="{{ route('admin.topics.index') }}" class="btn btn-secondary">Cancel</a>
            </form>
        </div>
    </div>
@endsection