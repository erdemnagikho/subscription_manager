<?php

namespace Database\Seeders;

use App\Models\Application;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    /**
     * @return void
     */
    public function run()
    {
        $apps = [
            [
                'name' => 'What An App',
            ],
            [
                'name' => 'Wow App',
            ],
        ];

        foreach ($apps as $app) {
            Application::create([
               'name' => $app['name'],
            ]);
        }
    }
}
