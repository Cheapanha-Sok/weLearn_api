<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Choice;
use App\Models\ExamDate;
use App\Models\Level;
use App\Models\Question;
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
            ['name' => "panha", "email" => "panha@gmail.com", "is_graduate" => true, "is_admin" => true, 'password' => Hash::make('password')],
            ['name' => "lyhuy", "email" => "lyhuy@gmail.com", "is_graduate" => true, "is_admin" => true, 'password' => Hash::make('password')],
            ['name' => "rady", "email" => "rady@gmail.com", "is_graduate" => true, "is_admin" => true, 'password' => Hash::make('password')],
            ['name' => "reaksa", "email" => "reaksa@gmail.com", "is_graduate" => true, "is_admin" => true, 'password' => Hash::make('password')],
            ['name' => "huy", "email" => "huy@gmail.com", "is_graduate" => true, "is_admin" => true, 'password' => Hash::make('password')],
        ]);

        for ($i = 0; $i < 5; $i++) {
            $name = "user" . ($i + 1);
            $email = "user" . ($i + 1) . "@example.com";
            User::insert(['name' => $name, "email" => $email, "is_graduate" => false, "is_admin" => false, 'password' => Hash::make('password')]);
        }
        Rank::insert([
            ['point' => 80, 'user_id' => 1, 'category_id' => 1],
            ['point' => 70, 'user_id' => 2, 'category_id' => 2],
            ['point' => 60, 'user_id' => 3, 'category_id' => 1],
            ['point' => 50, 'user_id' => 4, 'category_id' => 2],
            ['point' => 40, 'user_id' => 5, 'category_id' => 1],
            ['point' => 30, 'user_id' => 6, 'category_id' => 3],
            ['point' => 20, 'user_id' => 7, 'category_id' => 1],
            ['point' => 10, 'user_id' => 8, 'category_id' => 3]
        ]);

        Question::insert([
            ['name' => "What is 5 plus 3?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 10 minus 5?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 6 times 2?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 12 divided by 3?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 7 plus 9?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 15 minus 8?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 3 times 4?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 20 divided by 4?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 9 plus 7?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 25 minus 12?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 6 times 6?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 36 divided by 6?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 8 plus 3?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 16 minus 9?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 4 times 5?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 40 divided by 8?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 12 plus 6?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 18 minus 7?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 7 times 3?", "level_id" => 1, "category_id" => 1, "is_graduate" => false],
            ['name' => "What is 21 divided by 3?", "level_id" => 1, "category_id" => 1, "is_graduate" => false]
        ]);

        Choice::insert([
            ['name' => 8, "is_correct" => true, "question_id" => 1],
            ['name' => 7, "is_correct" => false, "question_id" => 1],
            ['name' => 6, "is_correct" => false, "question_id" => 1],

            ['name' => 5, "is_correct" => true, "question_id" => 2],
            ['name' => 6, "is_correct" => false, "question_id" => 2],
            ['name' => 4, "is_correct" => false, "question_id" => 2],

            ['name' => 12, "is_correct" => true, "question_id" => 3],
            ['name' => 10, "is_correct" => false, "question_id" => 3],
            ['name' => 8, "is_correct" => false, "question_id" => 3],

            ['name' => 4, "is_correct" => true, "question_id" => 4],
            ['name' => 1, "is_correct" => false, "question_id" => 4],
            ['name' => 8, "is_correct" => false, "question_id" => 4],

            ['name' => 4, "is_correct" => true, "question_id" => 5],
            ['name' => 16, "is_correct" => false, "question_id" => 5],
            ['name' => 8, "is_correct" => false, "question_id" => 5],

            ['name' => 5, "is_correct" => true, "question_id" => 6],
            ['name' => 7, "is_correct" => false, "question_id" => 6],
            ['name' => 6, "is_correct" => false, "question_id" => 6],

            ['name' => 14, "is_correct" => true, "question_id" => 7],
            ['name' => 12, "is_correct" => false, "question_id" => 7],
            ['name' => 18, "is_correct" => false, "question_id" => 7],

            ['name' => 5, "is_correct" => true, "question_id" => 8],
            ['name' => 4, "is_correct" => false, "question_id" => 8],
            ['name' => 10, "is_correct" => false, "question_id" => 8],

            ['name' => 16, "is_correct" => true, "question_id" => 9],
            ['name' => 15, "is_correct" => false, "question_id" => 9],
            ['name' => 17, "is_correct" => false, "question_id" => 9],

            ['name' => 14, "is_correct" => true, "question_id" => 10],
            ['name' => 13, "is_correct" => false, "question_id" => 10],
            ['name' => 12, "is_correct" => false, "question_id" => 10],

            ['name' => 36, "is_correct" => true, "question_id" => 11],
            ['name' => 31, "is_correct" => false, "question_id" => 11],
            ['name' => 34, "is_correct" => false, "question_id" => 11],

            ['name' => 6, "is_correct" => true, "question_id" => 12],
            ['name' => 2, "is_correct" => false, "question_id" => 12],
            ['name' => 8, "is_correct" => false, "question_id" => 12],

            ['name' => 11, "is_correct" => true, "question_id" => 13],
            ['name' => 10, "is_correct" => false, "question_id" => 13],
            ['name' => 12, "is_correct" => false, "question_id" => 13],

            ['name' => 7, "is_correct" => true, "question_id" => 14],
            ['name' => 6, "is_correct" => false, "question_id" => 14],
            ['name' => 8, "is_correct" => false, "question_id" => 14],

            ['name' => 20, "is_correct" => true, "question_id" => 15],
            ['name' => 21, "is_correct" => false, "question_id" => 15],
            ['name' => 24, "is_correct" => false, "question_id" => 15],

            ['name' => 5, "is_correct" => true, "question_id" => 16],
            ['name' => 4, "is_correct" => false, "question_id" => 16],
            ['name' => 8, "is_correct" => false, "question_id" => 16],

            ['name' => 18, "is_correct" => true, "question_id" => 17],
            ['name' => 16, "is_correct" => false, "question_id" => 17],
            ['name' => 14, "is_correct" => false, "question_id" => 17],

            ['name' => 11, "is_correct" => true, "question_id" => 18],
            ['name' => 12, "is_correct" => false, "question_id" => 18],
            ['name' => 18, "is_correct" => false, "question_id" => 18],

            ['name' => 21, "is_correct" => true, "question_id" => 19],
            ['name' => 23, "is_correct" => false, "question_id" => 19],
            ['name' => 22, "is_correct" => false, "question_id" => 19],

            ['name' => 7, "is_correct" => true, "question_id" => 20],
            ['name' => 6, "is_correct" => false, "question_id" => 20],
            ['name' => 8, "is_correct" => false, "question_id" => 20],
        ]);
    }
}
