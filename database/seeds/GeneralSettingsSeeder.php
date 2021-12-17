<?php

use Illuminate\Database\Seeder;
use App\GeneralSettings;


class GeneralSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 1;
        $data->setting_name = 'General Settings';
        $data->field_label = 'Favicon (25*25)';
        $data->field_name = 'favicon';
        $data->field_type = 'file';
        $data->field_value = null;
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 1;
        $data->setting_name = 'General Settings';
        $data->field_label = 'Logo';
        $data->field_name = 'logo';
        $data->field_type = 'file';
        $data->field_value = null;
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 1;
        $data->setting_name = 'General Settings';
        $data->field_label = 'Application Name';
        $data->field_name = 'application_name';
        $data->field_type = 'text';
        $data->field_value = 'AYT';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 2;
        $data->setting_name = 'Email Settings';
        $data->field_label = 'Email From/ Reply to';
        $data->field_name = 'email_from';
        $data->field_type = 'text';
        $data->field_value = 'info@adiyogitechnosoft.com';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 2;
        $data->setting_name = 'Email Settings';
        $data->field_label = 'SMTP Host';
        $data->field_name = 'smtp_host';
        $data->field_type = 'text';
        $data->field_value = 'adiyogitechnosoft.com';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 2;
        $data->setting_name = 'Email Settings';
        $data->field_label = 'SMTP Port';
        $data->field_name = 'smtp_port';
        $data->field_type = 'text';
        $data->field_value = '465';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 2;
        $data->setting_name = 'Email Settings';
        $data->field_label = 'SMTP User';
        $data->field_name = 'smtp_user';
        $data->field_type = 'text';
        $data->field_value = 'info@adiyogitechnosoft.com';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 2;
        $data->setting_name = 'Email Settings';
        $data->field_label = 'SMTP Password';
        $data->field_name = 'smtp_pass';
        $data->field_type = 'text';
        $data->field_value = 'ayt@123#176';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();



        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 3;
        $data->setting_name = 'Social Settings';
        $data->field_label = 'Facebook Link';
        $data->field_name = 'facebook_link';
        $data->field_type = 'text';
        $data->field_value = 'https://www.facebook.com/adiyogijodhpur';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 3;
        $data->setting_name = 'Social Settings';
        $data->field_label = 'Twitter Link';
        $data->field_name = 'twitter_link';
        $data->field_type = 'text';
        $data->field_value = 'https://www.twitter.com/adiyogijodhpur';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

        $data = new GeneralSettings();
        $data->order = 1;
        $data->setting_type = 3;
        $data->setting_name = 'Social Settings';
        $data->field_label = 'Google Plus';
        $data->field_name = 'google_link';
        $data->field_type = 'text';
        $data->field_value = 'https://www.google.com';
        $data->field_options = null;
        $data->is_require = '1';
        $data->save();

    }
}
