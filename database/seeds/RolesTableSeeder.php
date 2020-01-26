<?php

use Illuminate\Database\Seeder;
use App\Models\Role;

use App\Enums\Field;

class RolesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Role::create([
            "id" => Field::ID_ROLE_SUPERADMIN,
            "name" => "superadmin",
            "description" => "super administrador..."
        ]);
        Role::create([
            "id" => Field::ID_ROLE_ADMIN,
            "name" => "admin",
            "description" => "administrador..."
        ]);
        Role::create([
            "id" => Field::ID_ROLE_USER,
            "name" => "usuario",
            "description" => "usuario..."
        ]);
        Role::create([
            "id" => Field::ID_ROLE_GUESS,
            "name" => "invitado",
            "description" => "invitado..."
        ]);
    }
}
