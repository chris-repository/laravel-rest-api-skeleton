<?php

class ExampleItemSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        entity(App\Models\ExampleItem::class, 10)->create();
    }
}