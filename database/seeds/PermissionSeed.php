<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeed extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (config('permissions') as $key => $permissions_category) {        
            foreach ($permissions_category as $inner_key => $permissions) {           
                 Permission::create([
                  "name" => $permissions['name'],
                  "description" => $permissions['description'],
                    ]);
            }
        }
    }
}