<?php

use Carbon\Carbon;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class RootSeeder extends Seeder
{
    const HELL_ID = 999;

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        DB::table('users')->insert([
            'id' => self::HELL_ID,
            'name' => 'root',
            'email' => 'root@example.com',
            'password' => '123'
        ]);

        DB::table('rooms')->insert([
            'id' => self::HELL_ID,
            'user_id' => self::HELL_ID,
            'name' => 'root room',
            'status' => 0,
            'required_name' => 0,
            'shuffle_question' => 0,
            'shuffle_answer' => 0,
        ]);

        DB::table('quizzes')->insert([
            'id' => self::HELL_ID,
            'title' => 'root title',
            'user_id' => self::HELL_ID
        ]);

        DB::table('quiz_copies')->insert([
            'id' => self::HELL_ID,
            'title' => 'root title',
            'quiz_id' => self::HELL_ID
        ]);

        DB::table('result_tests')->insert([
            'id' => self::HELL_ID,
            'room_id' => self::HELL_ID,
            'user_id' => self::HELL_ID,
            'quiz_copy_id' => self::HELL_ID,
            'date_create' => Carbon::now()->toDateTimeString(),
        ]);
    }
}
