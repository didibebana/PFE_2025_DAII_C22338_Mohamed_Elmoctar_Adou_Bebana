<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        Role::create(['nom' => 'administrateur']);
        Role::create(['nom' => 'coordinateur_axe']);
        Role::create(['nom' => 'responsable_action']);
        Role::create(['nom' => 'consultant']);
    }
}

