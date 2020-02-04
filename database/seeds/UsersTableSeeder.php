<?php

use Illuminate\Database\Seeder;
use App\Models\User;

use App\Enums\Field;

class UsersTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // to permite create not null modified_by_id
        User::flushEventListeners();

        User::create([
            "names" => "Christian",
            "surnames" => "Carrillo",
            "email" => "ciber@gmail.com",
            "doc_num" => "44084911",
            "password" => bcrypt(12345678),
            "role_id" => Field::ID_ROLE_ADMIN,
            "modified_by_id" => 1
        ]);
    }
}
