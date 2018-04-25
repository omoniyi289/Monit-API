<?php

use App\Permission;
use Illuminate\Database\Seeder;

class PermissionSeeder extends Seeder
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
                $perm = Permission::where('UI_slug', $permissions['UI_slug'])->get()->first();      
                 if(count($perm) == 0){
                 Permission::create([
                  "name" => $permissions['name'],
                  "description" => $permissions['description'],
                  "UI_slug" => $permissions['UI_slug'],
                    ]);
             }

            }
        }
    }
}