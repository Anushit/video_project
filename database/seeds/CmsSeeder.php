<?php

use Illuminate\Database\Seeder;
use App\Cms;

class CmsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new Cms();
        $data->slug = 'about';
        $data->name = 'About';
        $data->title = 'About';
        $data->content = 'About';
        $data->meta_title = 'About';
        $data->meta_keyword = 'About';
        $data->meta_description = 'About';
        $data->save();

        $data = new Cms();
        $data->slug = 'contact-us';
        $data->name = 'Contact Us';
        $data->title = 'Contact Us';
        $data->content = 'Contact Us';
        $data->meta_title = 'Contact Us';
        $data->meta_keyword = 'Contact Us';
        $data->meta_description = 'Contact Us';
        $data->save();

        $data = new Cms();
        $data->slug = 'privacy';
        $data->name = 'Privacy & Policies';
        $data->title = 'Privacy & Policies';
        $data->content = 'Privacy & Policies';
        $data->meta_title = 'Privacy & Policies';
        $data->meta_keyword = 'Privacy & Policies';
        $data->meta_description = 'Privacy & Policies';
        $data->save();

        $data = new Cms();
        $data->slug = 'terms';
        $data->name = 'Terms & Conditions';
        $data->title = 'Terms & Conditions';
        $data->content = 'Terms & Conditions';
        $data->meta_title = 'Terms & Conditions';
        $data->meta_keyword = 'Terms & Conditions';
        $data->meta_description = 'Terms & Conditions';
        $data->save();
    }
}
