<?php

namespace Database\Seeders;

use App\Models\City;
use App\Models\Customer;
use App\Models\Governorate;
use App\Models\Manager;
use App\Models\Product;
use App\Models\Store;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Install Spatie Shield
        Artisan::call('shield:install admin');
        Artisan::call('shield:generate --all --panel=admin');
        $this->command->info("Shield Installation is completed successfully");
        // Create roles
        $superAdminRole = Role::where('name' , 'super_admin')->first();
        $adminRole = Role::create(['name' => 'admin', 'guard_name' => 'web']);
        $managerRole = Role::create(['name' => 'manager', 'guard_name' => 'web']);
        $courierRole = Role::create(['name' => 'courier', 'guard_name' => 'web']);
        $salesRole = Role::create(['name' => 'sales', 'guard_name' => 'web']);

        $this->command->info("Roles Has Created successfully");

        // Assign permissions to roles
        $permissions = Permission::all();
        $superAdminRole->syncPermissions($permissions); // Super Admin gets all permissions
        $adminRole->syncPermissions($permissions); // Admin gets all permissions
        $managerRole->syncPermissions([
            'view_product',
            'view_any_product',
            'create_product',
            'update_product',
            'delete_product',
            'delete_any_product',
        ]);

        $courierRole->syncPermissions([
            'view_order',
            'update_order',
            'view_any_order',
        ]);

        $salesRole->syncPermissions([
            'view_order',
            'view_any_order',
            'create_order',
            'update_order',
            'delete_order',
            'delete_any_order',
            'view_city',
            'view_any_city',
            'view_governorate',
            'view_any_governorate',
            'view_customer',
            'view_any_customer',
            'create_customer',
            'update_customer',
            'delete_customer',
            'delete_any_customer',
        ]);

        $this->command->info("Permissions assigned to roles successfully");

        // Create users
        User::create([
            'name' => 'Abdo Shrief',
            'phone' => '01270989676',
            'email' => 'abdo.shrief270@gmail.com',
            'type' => 'admin',
            'password' => Hash::make('12345678'),
            'active' => true
        ]);

        User::create([
            'name' => 'Abdallah Ayman',
            'phone' => '01115716930',
            'type' => 'admin',
            'password' => Hash::make('12345678'),
            'active' => true
        ]);
        Artisan::call('shield:super-admin --user=1 --panel=admin');
        $this->command->info("Admins Has Created successfully");

        // Create a courier
        $sales=User::create([
            'name' => 'مبيعات 1',
            'phone' => '01000500500',
            'email' => 'sales@gmail.com',
            'type' => 'sales',
            'password' => Hash::make('12345678'),
            'active' => true
        ]);
        $this->command->info("Sales Accounts Has Created successfully");

        // Seed governorates
        $governorate = Governorate::create(['name' => 'الجيزة']);
        $this->command->info("Governorates Has Created successfully");
        // Create a courier
        $courier=User::create([
            'name' => 'مندوب 1',
            'phone' => '01000500300',
            'email' => 'courier@gmail.com',
            'type' => 'courier',
            'governorate_id' => $governorate->id,
            'password' => Hash::make('12345678'),
            'active' => true
        ]);
        $this->command->info("Couriers Has Created successfully");


        $city = City::create(['name' => 'الجيزة', 'governorate_id' => $governorate->id,'delivery_man_id'=>$courier->id, 'shipping_cost' => 50]);
        $this->command->info("Cities Has Created successfully");

        // Create a customer
        Customer::create([
            'name' => 'customer 1',
            'phone' => '01000300300',
            'whatsapp' => '01000300300',
            'email' => 'customer@gmail.com',
            'buy_count' => 0,
            'city_id' => $city->id,
            'address' => "Address of the Customer one"
        ]);
        $this->command->info("Customers Has Created successfully");

        // Create a manager
        $manager = User::create([
            'name' => 'مدير 1',
            'phone' => '01000200200',
            'email' => 'manager@gmail.com',
            'type' => 'manager',
            'password' => Hash::make('12345678'),
            'active' => true
        ]);
        $this->command->info("Managers Has Created successfully");

        // Create a store
        Store::create([
            'name' => 'مخزن 1',
            'manager_id' => $manager->id
        ]);
        $this->command->info("Stores Has Created successfully");

        // Create a product
        Product::create([
            'name' => 'بلح نص جاف',
            'code' => 'بلح 1',
            'buy_price' => 180,
            'net_price' => 200,
            'price' => 200,
            'quantity' => 100,
            'store_id' => 1
        ]);
        $this->command->info("Products Has Created successfully");

    }
}
