<?php

namespace Database\Seeders;

use App\Enums\Sex;
use App\Enums\SkillLevel;
use App\Models\Person;
use App\Models\User;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Клієнт 1 - сім'я з дітьми
        $user1 = User::create([
            'first_name' => 'Олександр',
            'last_name' => 'Петренко',
            'email' => 'petrenko@example.com',
            'phone' => '+380501234567',
            'password' => 'password',
        ]);

        Person::create([
            'user_id' => $user1->id,
            'first_name' => 'Олександр',
            'phone' => '+380501234567',
            'birth_date' => '1985-03-15',
            'skill_level' => SkillLevel::Intermediate,
            'sex' => Sex::Male,
            'lessons_count' => 5,
        ]);

        Person::create([
            'user_id' => $user1->id,
            'first_name' => 'Марія',
            'phone' => '+380501234568',
            'birth_date' => '1988-07-22',
            'skill_level' => SkillLevel::Novice,
            'sex' => Sex::Female,
            'lessons_count' => 3,
        ]);

        Person::create([
            'user_id' => $user1->id,
            'first_name' => 'Максим',
            'phone' => null,
            'birth_date' => '2015-01-10',
            'skill_level' => SkillLevel::Beginner,
            'sex' => Sex::Male,
            'lessons_count' => 2,
        ]);

        // Клієнт 2 - молода пара
        $user2 = User::create([
            'first_name' => 'Андрій',
            'last_name' => 'Коваленко',
            'email' => 'kovalenko@example.com',
            'phone' => '+380672345678',
            'password' => 'password',
        ]);

        Person::create([
            'user_id' => $user2->id,
            'first_name' => 'Андрій',
            'phone' => '+380672345678',
            'birth_date' => '1995-11-05',
            'skill_level' => SkillLevel::Pro,
            'sex' => Sex::Male,
            'lessons_count' => 15,
        ]);

        Person::create([
            'user_id' => $user2->id,
            'first_name' => 'Юлія',
            'phone' => '+380672345679',
            'birth_date' => '1997-04-18',
            'skill_level' => SkillLevel::Intermediate,
            'sex' => Sex::Female,
            'lessons_count' => 8,
        ]);

        // Клієнт 3 - початківець
        $user3 = User::create([
            'first_name' => 'Ірина',
            'last_name' => 'Шевченко',
            'email' => 'shevchenko@example.com',
            'phone' => '+380933456789',
            'password' => 'password',
        ]);

        Person::create([
            'user_id' => $user3->id,
            'first_name' => 'Ірина',
            'phone' => '+380933456789',
            'birth_date' => '1990-09-30',
            'skill_level' => SkillLevel::Beginner,
            'sex' => Sex::Female,
            'lessons_count' => 0,
        ]);

        // Клієнт 4 - з дітьми різного віку
        $user4 = User::create([
            'first_name' => 'Віктор',
            'last_name' => 'Бондаренко',
            'email' => 'bondarenko@example.com',
            'phone' => '+380504567890',
            'password' => 'password',
        ]);

        Person::create([
            'user_id' => $user4->id,
            'first_name' => 'Софія',
            'phone' => null,
            'birth_date' => '2020-06-12',
            'skill_level' => SkillLevel::Beginner,
            'sex' => Sex::Female,
            'lessons_count' => 0,
        ]);

        Person::create([
            'user_id' => $user4->id,
            'first_name' => 'Дмитро',
            'phone' => null,
            'birth_date' => '2014-02-28',
            'skill_level' => SkillLevel::Novice,
            'sex' => Sex::Male,
            'lessons_count' => 4,
        ]);

        Person::create([
            'user_id' => $user4->id,
            'first_name' => 'Анна',
            'phone' => '+380504567891',
            'birth_date' => '2008-12-01',
            'skill_level' => SkillLevel::Intermediate,
            'sex' => Sex::Female,
            'lessons_count' => 10,
        ]);
    }
}
