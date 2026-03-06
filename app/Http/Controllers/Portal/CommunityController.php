<?php

namespace App\Http\Controllers\Portal;

use App\Http\Controllers\Controller;
use App\Enums\PostStatus;
use App\Models\Post;
use App\Models\PostComment;
use Illuminate\Http\Request;

class CommunityController extends Controller
{
    public function index()
    {
        $posts = Post::where('status', PostStatus::Published)
            ->with('user')
            ->withCount('comments')
            ->latest('published_at')
            ->paginate(15);

        return view('portal.community.index', compact('posts'));
    }

    public function show(Post $post)
    {
        abort_unless($post->status === PostStatus::Published, 404);
        $post->load(['user', 'comments.user', 'comments.replies.user']);
        return view('portal.community.show', compact('post'));
    }

    public function comment(Request $request, Post $post)
    {
        $request->validate([
            'body' => 'required|string|max:2000',
            'parent_id' => 'nullable|exists:post_comments,id',
        ]);

        PostComment::create([
            'post_id' => $post->id,
            'user_id' => auth()->id(),
            'body' => $request->body,
            'parent_id' => $request->parent_id,
        ]);

        return back()->with('success', 'Comment posted.');
    }
}
