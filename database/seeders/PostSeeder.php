<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Post;

class PostSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //実行しない
        \Event::fakeFor(function () {
            //50件データ
            Post::factory()->count(50)->create();
        });
    }
}
