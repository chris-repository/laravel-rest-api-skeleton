<?php

class DatabaseSeeder extends \Illuminate\Database\Seeder
{
    public function run()
    {
        $this->call(ExampleItemSeeder::class);
    }
}