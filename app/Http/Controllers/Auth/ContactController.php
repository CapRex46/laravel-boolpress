<?php

namespace App\Http\Controllers\Admin;

use App\Category;
use App\Http\Controllers\Controller;
use App\Post;
use App\Tag;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class PostController extends Controller {
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index() {
    // $posts = Post::all();
    $posts = Post::where("user_id", Auth::user()->id)->get();

    return view("admin.posts.index", compact("posts"));
  }

  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create() {
    $categories = Category::all();
    $tags = Tag::all();

    return view("admin.posts.create", compact("categories", "tags"));
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request) {
    $data = $request->validate([
      "title" => "required|min:5",
      "content" => "required|min:20",
      "category_id" => "nullable",
      "tags" => "nullable",
      "coverImg" => "nullable|image|max:500"
    ]);

    $post = new Post();
    $post->fill($data);

    // Genero lo slug partendo dal titolo
    $slug = Str::slug($post->title);

    // controllo a db se esiste già un elemento con lo stesso slug
    $exists = Post::where("slug", $slug)->first();
    $counter = 1;

    // Fintanto che $exists ha un valore diverso da null o false,
    // eseguo il while
    while ($exists) {
      // Genero un nuovo slug, prendendo quello precedente e concatenando un numero incrementale
      $newSlug = $slug . "-" . $counter;
      $counter++;

      // controllo a db se esiste già un elemento con i nuovo slug appena generato
      $exists = Post::where("slug", $newSlug)->first();

      // Se non esiste, salvo il nuovo slug nella variabile $slub che verrà poi usata
      // per assegnare il valore all'interno del nuovo post.
      if (!$exists) {
        $slug = $newSlug;
      }
    }

    // Assegno il valore di slug al nuovo post
    $post->slug = $slug;
    $post->user_id = Auth::user()->id;

    // se la chiave esiste l'utente sta cercando di uploadare un file
    if (key_exists("coverImg", $data)) {
      $post->coverImg = Storage::put("postCovers", $data["coverImg"]);
    }

    $post->save();

    // Per il post corrente, aggiungo le relazioni con i tag ricevuti
    // E' essenziale che attach avvenga SOLO DOPO che il post è stato salvato,
    // Altrimenti non avremo l'id del nuovo post, in quanto questo viene creato nel momento del salvataggio.
    $post->tags()->attach($data["tags"]);

    return redirect()->route("admin.posts.index");
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($slug) {
    $post = Post::where("slug", $slug)->first();

    return view("admin.posts.show", compact("post"));
  }

  /**
   * Show the form for editing the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function edit($slug) {
    $post = Post::where("slug", $slug)->first();
    $categories = Category::all();
    $tags = Tag::all();

    return view("admin.posts.edit", [
      "post" => $post,
      "categories" => $categories,
      "tags" => $tags
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, $id) {
    $data = $request->validate([
      "title" => "required|min:5",
      "content" => "required|min:20",
      "category_id" => "nullable|exists:categories,id",
      "tags" => "nullable|exists:tags,id",
      "coverImg" => "nullable|image|max:500"
    ]);

    $post = Post::findOrFail($id);

    if ($data["title"] !== $post->title) {
      // Genero lo slug partendo dal titolo
      // $slug = Str::slug($data["title"]);

      // controllo a db se esiste già un elemento con lo stesso slug
      // $exists = Post::where("slug", $slug)->first();
      // $counter = 1;

      // Fintanto che $exists ha un valore diverso da null o false,
      // eseguo il while
      /* while ($exists) {
        // Genero un nuovo slug, prendendo quello precedente e concatenando un numero incrementale
        $newSlug = $slug . "-" . $counter;
        $counter++;

        // controllo a db se esiste già un elemento con i nuovo slug appena generato
        $exists = Post::where("slug", $newSlug)->first();

        // Se non esiste, salvo il nuovo slug nella variabile $slub che verrà poi usata
        // per assegnare il valore all'interno del nuovo post.
        if (!$exists) {
          $slug = $newSlug;
        }
      } */

      // $post->slug = $slug;
      // $data["slug"] = $slug;

      $data["slug"] = $this->generateUniqueSlug($data["title"]);
    }

    // $post->category_id = $data["category_id"];
    // update fa sia fill che save in un colpo solo.
    $post->update($data);

    // se data contiene la chiave "coverImg", indica che l'utente sta caricando un file
    if (key_exists("coverImg", $data)) {
      // controllare se a db esiste già un immagine
      // Se si, PRIMA di caricare quella nuova, cancelliamo quella vecchia
      if ($post->coverImg) {
        Storage::delete($post->coverImg);
      }

      $coverImg = Storage::put("postCovers", $data["coverImg"]);

      $post->coverImg = $coverImg;
      $post->save();
    }

    if (key_exists("tags", $data)) {
      // Aggiorniamo anche la tabella poste post_tag

      // Per il post corrente, dalla tabella ponte, rimuovo TUTTE le relazioni esistenti con i tag
      // $post->tags()->detach();

      // Per il post corrente, aggiungo le relazioni con i tag ricevuti
      // $post->tags()->attach($data["tags"]);

      // Farà prima il detach SOLO degli elementi che non sono più presenti nel nuovo array ricevuto dal form
      // Farà eventualmente l'attach SOLO dei nuovi elementi
      // I tag che c'erano prima e ci sono anche ora, non verranno toccati.
      $post->tags()->sync($data["tags"]);
    }

    return redirect()->route("admin.posts.show", $post->slug);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy($id) {
    $post = Post::findOrFail($id);

    // toglie tutti i collegamenti con eventuali tag
    $post->tags()->detach();
    // $post->tags()->sync([]);

    if ($post->coverImg) {
      Storage::delete($post->coverImg);
    }

    $post->delete();

    return redirect()->route("admin.posts.index");
  }

  protected function generateUniqueSlug($postTitle) {
    // Genero lo slug partendo dal titolo
    $slug = Str::slug($postTitle);

    // controllo a db se esiste già un elemento con lo stesso slug
    $exists = Post::where("slug", $slug)->first();
    $counter = 1;

    // Fintanto che $exists ha un valore diverso da null o false,
    // eseguo il while
    while ($exists) {
      // Genero un nuovo slug, prendendo quello precedente e concatenando un numero incrementale
      $newSlug = $slug . "-" . $counter;
      $counter++;

      // controllo a db se esiste già un elemento con i nuovo slug appena generato
      $exists = Post::where("slug", $newSlug)->first();

      // Se non esiste, salvo il nuovo slug nella variabile $slub che verrà poi usata
      // per assegnare il valore all'interno del nuovo post.
      if (!$exists) {
        $slug = $newSlug;
      }
    }

    return $slug;
  }
}