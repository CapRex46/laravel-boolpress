<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Post;
use App\Traits\SlugGenerator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class PostController extends Controller {
  use SlugGenerator;

  public function index(Request $request) {
    $filter = $request->input("filter");

    if ($filter) {
      $posts = Post::where("title", "LIKE", "%$filter%")->paginate(4);
    } else {
      $posts = Post::paginate(4);
    }

    $posts->load("user", "category", "tags");

    /* return response()->json([
      "esito" => "ok",
      "dataRichiesta" => now(),
      "data" => $posts
    ]); */

    $posts->each(function ($post) {
      // se il post ha una coverImg,
      // allora sostituisco il valore con l'url completo per quell'immagine
      if ($post->coverImg) {
        $post->coverImg = asset("storage/" . $post->coverImg);
      }else{
        $post->coverImg = "https://via.placeholder.com/1024x480";
      }
    });

    return response()->json($posts);
  }

  public function store(Request $request) {
    $data = $request->validate([
      "title" => "required|min:5",
      "content" => "required|min:20",
      "category_id" => "nullable",
      "tags" => "nullable"
    ]);

    $newPost = new Post();
    $newPost->fill($data);
    $newPost->user_id = 16;
    $newPost->slug = $this->generateUniqueSlug($data["title"]);
    $newPost->save();

    if (key_exists("tags", $data)) {
      $newPost->tags()->attach($data["tags"]);
    }

    return response()->json($newPost);
  }

  public function show($slug) {
    $post = Post::where("slug", $slug)
      ->with(["tags", "user", "category"])
      ->first();
    // $post = Post::where("id", $id)->with(["tags", "user", "category"])->first();

    // $post->load("user");

    if (!$post) {
      abort(404);
    }

    return response()->json($post);
  }
  public function destroy($slug){
    $post = Post::where("slug", $slug)->first();

    // toglie tutti i collegamenti con eventuali tag
    $post->tags()->detach();

    if ($post->coverImg) {
      Storage::delete($post->coverImg);
    }

    $post->delete();

    return response()->json();
  }
}