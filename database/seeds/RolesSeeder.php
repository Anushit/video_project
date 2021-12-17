<?php

use Illuminate\Database\Seeder;
use App\Roles;

class RolesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $role = new Roles();
        $role->name = 'Owner';
        $role->display_name = 'owner';
        $role->description = 'I am owner';
        $role->modules_permission = json_encode(array("roles","roles.add","roles.edit","users","users.add","users.edit","customers","customers.add","customers.edit","cms.pages","cms.pages.edit","general.settings","general.settings.edit","categories","categories.add","categories.edit","videos","videos.add","videos.edit","videos.remarks","banners","banners.add","banners.edit","plans","plans.add","plans.edit"));
        $role->status = '0';
        $role->save();

        $role = new Roles();
        $role->name = 'Admin';
        $role->display_name = 'admin';
        $role->description = 'I am admin';
        $role->modules_permission = json_encode(array("roles","roles.add","roles.edit","users","users.add","users.edit","customers","customers.add","customers.edit","cms.pages","cms.pages.edit","general.settings","general.settings.edit","categories","categories.add","categories.edit","videos","videos.add","videos.edit","videos.remarks","banners","banners.add","banners.edit","plans","plans.add","plans.edit"));
        $role->status = '0';
        $role->save();
    }
}
