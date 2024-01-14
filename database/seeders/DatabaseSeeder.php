<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Enums\User\Role;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Model::withoutEvents(function () {
            // \App\Models\User::factory(10)->create();

            \App\Models\User::factory()->create([
                'name' => 'Super Admin',
                'email' => 'su@coderslab.com.bd',
                'role' => Role::SuperAdmin->value,
            ]);
        });
    }
}
