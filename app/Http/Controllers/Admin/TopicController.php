<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\Topic;
use Illuminate\Http\Request;

class TopicController extends Controller
{
    public function index()
    {
        $search = trim((string) request('search'));

        $topics = Topic::with('category')
            ->when($search !== '', function ($query) use ($search) {
                $query->where(function ($innerQuery) use ($search) {
                    $innerQuery->where('name', 'like', '%' . $search . '%')
                        ->orWhere('description', 'like', '%' . $search . '%')
                        ->orWhereHas('category', function ($categoryQuery) use ($search) {
                            $categoryQuery->where('name', 'like', '%' . $search . '%');
                        });
                });
            })
            ->latest()
            ->get();

        return view('admin.topics.index', compact('topics', 'search'));
    }

    public function create()
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.topics.create', compact('categories'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        Topic::create([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic created successfully.');
    }

    public function edit(Topic $topic)
    {
        $categories = Category::orderBy('name')->get();
        return view('admin.topics.edit', compact('topic', 'categories'));
    }

    public function update(Request $request, Topic $topic)
    {
        $request->validate([
            'category_id' => ['required', 'exists:categories,id'],
            'name' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
        ]);

        $topic->update([
            'category_id' => $request->category_id,
            'name' => $request->name,
            'description' => $request->description,
        ]);

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic updated successfully.');
    }

    public function destroy(Topic $topic)
    {
        $topic->delete();

        return redirect()->route('admin.topics.index')
            ->with('success', 'Topic deleted successfully.');
    }
}
