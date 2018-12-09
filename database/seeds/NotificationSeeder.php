<?php

use App\Models\NotificationModules;
use Illuminate\Database\Seeder;

class NotificationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        foreach (config('notifications') as $key => $permissions_category) {        
            foreach ($permissions_category as $inner_key => $permissions) {
            $perm = NotificationModules::where('UI_slug', $permissions['UI_slug'])->get()->first();      
                 if(count($perm) == 0){           
                 NotificationModules::create([
                  "name" => $permissions['name'],
                  "UI_slug" => $permissions['UI_slug'],
                    ]);
             }
            }
        }
    }
}