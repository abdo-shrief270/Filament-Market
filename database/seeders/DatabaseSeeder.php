<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        Artisan::call('shield:install admin');
        Artisan::call('shield:generate --all --panel=admin');
        \Spatie\Permission\Models\Role::create(['name'=>'admin', 'guard_name'=>'web']);
        \App\Models\User::create([
            'name'=>'Abdo Shrief',
            'phone'=>'01270989676',
            'email'=>'abdo.shrief270@gmail.com',
            'type'=>'admin',
            'password'=>\Illuminate\Support\Facades\Hash::make('12345678'),
            'active'=>true
        ]);
        \App\Models\User::create([
            'name'=>'Abdallah Ayman',
            'phone'=>'01115716930',
            'type'=>'admin',
            'password'=>\Illuminate\Support\Facades\Hash::make('15354652'),
            'active'=>true
        ]);
        Artisan::call('shield:super-admin --user=1');
        \Spatie\Permission\Models\Role::create(['name'=>'manager', 'guard_name'=>'web']);
        \Spatie\Permission\Models\Role::create(['name'=>'courier', 'guard_name'=>'web']);
    }
}
