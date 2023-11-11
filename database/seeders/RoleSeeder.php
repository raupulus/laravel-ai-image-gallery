<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Seeder;

class RoleSeeder extends Seeder
{
    private $datas = [
        [
            'slug' => "admin",
            'name' => "Admin",
            'show_name' => 'Admin',
            'description' => "Site Admin"
        ],
    ];


    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach($this->datas as $data) {
            Role::firstOrCreate([
                    'slug' => $data['slug']
                ], [
                    'name' => $data['name'],
                    'show_name' => $data['show_name'],
                    'description' => $data['description']
                ]
            );
        }
    }
}
