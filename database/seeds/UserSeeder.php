<?php

use Illuminate\Database\Seeder;
use App\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new User();
        $data->name = 'Admin';
        $data->username = 'admin';
        $data->email = 'admin@admin.com';
        $data->slug = 'admins';
        $data->role_id = 1;
        $data->password = Hash::make('12345678');
        $data->save();
    }
}
