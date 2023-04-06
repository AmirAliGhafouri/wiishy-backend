<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class userSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name'=>'علی',
            'family'=>'احمدی',
            'userBirthday'=>'1378/09/03',
            'userLocationid'=>2,
            'userGender'=>1,
            'userDescription'=>'لورم ایپسوم متن ساختگی با تولید سادگی نامفهوم از صنعت چاپ و با استفاده از طراحان گرافیک است چاپگرها و متون بلکه روزنامه و مجله در ستون و سطرآنچنان که لازم است ',
            'userImageUrl'=>'test',
            'status'=>1,
            'userCode'=>1543,
            ]);
    }
}
