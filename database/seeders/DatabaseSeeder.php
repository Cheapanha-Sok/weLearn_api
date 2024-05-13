<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\ExamDate;
use App\Models\Level;
use App\Models\Rank;
use App\Models\Type;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        Type::insert([
            ['name' => 'science'],
            ['name' => 'socialscience']
        ]);
        Category::insert([
            ['name' => 'math'],
            ['name' => 'khmer'],
            ['name' => 'physic'],
            ['name' => 'english'],
            ['name' => 'chemitry'],

            ['name' => 'math'],
            ['name' => 'khmer'],
            ['name' => 'english'],
            ['name' => 'history'],
        ]);
        DB::table('category_type')->insert([
            ['type_id' => 1, 'category_id' => 1],
            ['type_id' => 1, 'category_id' => 2],
            ['type_id' => 1, 'category_id' => 3],
            ['type_id' => 1, 'category_id' => 4],
            ['type_id' => 1, 'category_id' => 5],

            ['type_id' => 2, 'category_id' => 6],
            ['type_id' => 2, 'category_id' => 7],
            ['type_id' => 2, 'category_id' => 8],
            ['type_id' => 2, 'category_id' => 9],

        ]);

        Level::insert([
            ['name' => 'easy', 'point' => 2],
            ['name' => 'meduim', 'point' => 4],
            ['name' => 'hard', 'point' => 6]
        ]);

        ExamDate::insert([
            ['name' => '2017'],
            ['name' => '2018'],
            ['name' => '2019'],
            ['name' => '2020'],
            ['name' => '2021'],
            ['name' => '2022'],
            ['name' => '2023'],
        ]);


        User::insert([
            ['name' => "panha", "email" => "panha@gmail.com", "isGraduate" => false, "isAdmin" => true, 'password' => Hash::make('password')],
            ['name' => "lyhuy", "email" => "lyhuy@gmail.com", "isGraduate" => false, "isAdmin" => true, 'password' => Hash::make('password')],
            ['name' => "rady", "email" => "rady@gmail.com", "isGraduate" => false, "isAdmin" => true, 'password' => Hash::make('password')],
            ['name' => "reaksa", "email" => "reaksa@gmail.com", "isGraduate" => false, "isAdmin" => true, 'password' => Hash::make('password')],
            ['name' => "huy", "email" => "huy@gmail.com", "isGraduate" => false, "isAdmin" => true, 'password' => Hash::make('password')],
        ]);

        for ($i = 0; $i < 5; $i++) {
            $name = "user" . ($i + 1);
            $email = "user" . ($i + 1) . "@example.com";
            User::insert(['name' => $name, "email" => $email, "isGraduate" => false, "isAdmin" => false, 'password' => Hash::make('password')]);
        }
        Rank::insert([
            ['point' => 80, 'user_id' => 1],
            ['point' => 70, 'user_id' => 2],
            ['point' => 60, 'user_id' => 3],
            ['point' => 50, 'user_id' => 4],
            ['point' => 40, 'user_id' => 5],
            ['point' => 30, 'user_id' => 6],
            ['point' => 20, 'user_id' => 7],
            ['point' => 10, 'user_id' => 8],
        ]);
    }
}
