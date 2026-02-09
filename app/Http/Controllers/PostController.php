<?php

namespace App\Http\Controllers;

// For some reason, this different version of the controller needs to be imported
use Illuminate\Routing\Controller;
use App\Models\Post;
use Illuminate\Http\Request;
use App\Http\Resources\PostResource;
use App\Http\Resources\CommentResource;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Gate;

class PostController extends Controller
{
    use AuthorizesRequests;

    public function __construct()
    {
        $this->authorizeResource(Post::class);
    }

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return inertia('Posts/Index', [
            'posts' => PostResource::collection(Post::with('user')->latest()->latest('id')->paginate())
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return inertia('Posts/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'title' => ['required', 'string', 'min:10', 'max:120'],
            'body' => ['required', 'string', 'min:20', 'max:10000'],
        ]);

        // ---------------------------------------
        // PREVIOUS VERSION - WORKS WITHOUT Model::unguard()
        // $post = Post::make($data);

        // $post->user()->associate($request->user())
        //     ->save();
        // ---------------------------------------

        // Needed to add Model::unguard() in AppServiceProvider for this to work
        $post = Post::create([
            ...$data,
            'user_id' => $request->user()->id,
        ]);

        return to_route('posts.show', $post);
    }

    /**
     * Display the specified resource.
     */
    public function show(Post $post)
    {
        $post->load('user');

        return inertia('Posts/Show', [
            'post' => fn () => PostResource::make($post),
            'comments' => fn () => CommentResource::collection($post->comments()->with('user')->latest()->latest('id')->paginate(10))
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Post $post)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Post $post)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Post $post)
    {
        //
    }
}
