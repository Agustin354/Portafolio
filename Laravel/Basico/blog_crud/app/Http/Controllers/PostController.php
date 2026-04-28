<?php

namespace App\Http\Controllers;

use App\Models\Post;
use Illuminate\Http\Request;

class PostController extends Controller
{
    public function index()
    {
        $posts = Post::latest()->paginate(10);
        return view('posts.index', compact('posts'));
    }

    public function show(Post $post)
    {
        return view('posts.show', compact('post'));
    }

    public function create()
    {
        return view('posts.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'titulo'    => 'required|min:3|max:100',
            'contenido' => 'required|min:10',
        ]);

        $post = Post::create($validated);
        return redirect()->route('posts.show', $post)->with('success', 'Post creado.');
    }

    public function edit(Post $post)
    {
        return view('posts.edit', compact('post'));
    }

    public function update(Request $request, Post $post)
    {
        $validated = $request->validate([
            'titulo'    => 'required|min:3|max:100',
            'contenido' => 'required|min:10',
        ]);

        $post->update($validated);
        return redirect()->route('posts.show', $post)->with('success', 'Post actualizado.');
    }

    public function destroy(Post $post)
    {
        $post->delete();
        return redirect()->route('posts.index')->with('success', 'Post eliminado.');
    }
}
