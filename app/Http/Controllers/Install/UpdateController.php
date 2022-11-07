<?php

namespace App\Http\Controllers\Install;

use App\Http\Controllers\Controller;
use Database\Seeders\EmailSMSTemplateSeeder;
use Illuminate\Support\Facades\Artisan;

class UpdateController extends Controller {

    public function update_migration() {
        Artisan::call('migrate', ['--force' => true]);

        //Version Update to 1.2
        if (get_option('APP_VERSION', 1.0) < 1.2) {
            Artisan::call('db:seed', ['--class' => EmailSMSTemplateSeeder::class, '--force' => true]);

            update_option('APP_VERSION', 1.2);
            echo "Version Updated 1.2<br>";
        }
        echo "Migration Updated successfully<br>";
    }
}
