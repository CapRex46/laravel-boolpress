<?php

use App\Tag;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TagsTableSeeder extends Seeder {
  /**
   * Run the database seeds.
   *
   * @return void
   */
  public function run() {
    $tags = ['carne', 'pesce', 'pippo', 'pluto', 'senza lattosio', 'senza glutine'];

    // Cancella tutte le righe della tabella e resetta anche l'indice autoincrement.
    Tag::truncate();

    foreach ($tags as $tag) {
      $newTag = new Tag();
      $newTag->name = $tag;
      $newTag->slug = Str::slug($tag) . 2;
      $newTag->save();
    }
  }
}