<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Post;
use App\Models\Branch;
use App\Models\Committee;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index(Request $request)
    {
        $query = Post::with(['user', 'branch', 'committee']);

        if ($request->filled('search')) {
            $query->where('title', 'like', '%' . $request->search . '%');
        }

        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }

        if ($request->filled('type')) {
            $query->where('type', $request->type);
        }

        $posts = $query->latest()->paginate(15)->withQueryString();

        return view('admin.posts.index', compact('posts'));
    }

    public function create()
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $committees = Committee::where('is_active', true)->orderBy('name')->get();

        return view('admin.posts.create', compact('branches', 'committees'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'committee_id' => 'nullable|exists:committees,id',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $validated['user_id'] = auth()->id();

        Post::create($validated);

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post created successfully.');
    }

    public function show(Post $post)
    {
        $post->load(['user', 'branch', 'committee', 'comments.user']);

        return view('admin.posts.show', compact('post'));
    }

    public function edit(Post $post)
    {
        $branches = Branch::where('is_active', true)->orderBy('name')->get();
        $committees = Committee::where('is_active', true)->orderBy('name')->get();

        return view('admin.posts.edit', compact('post', 'branches', 'committees'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'body' => 'required|string',
            'type' => 'required|string',
            'status' => 'required|string',
            'branch_id' => 'nullable|exists:branches,id',
            'committee_id' => 'nullable|exists:committees,id',
            'is_pinned' => 'boolean',
            'published_at' => 'nullable|date',
        ]);

        $post->update($validated);

        return redirect()->route('admin.posts.show', $post)
            ->with('success', 'Post updated successfully.');
    }

    public function destroy(Post $post)
    {
        $post->delete();

        return redirect()->route('admin.posts.index')
            ->with('success', 'Post deleted successfully.');
    }
}
