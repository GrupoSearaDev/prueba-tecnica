<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;
use Faker\Factory;
use App\Models\User;


class UserSeeder extends Seeder
{
    public function run()
    {
        $model = new User();
        $model->save($this->generateUserAdmin());
        
    }

    private function generateUserAdmin(){
        $faker = Factory::create();

        return [
            'name'=> $faker->name(),
            'lastname'=> $faker->lastname(),
            'phone'=> '1234567890',
            'email'=> $faker->email(),
            'photo'=> 'https://upload.wikimedia.org/wikipedia/commons/thumb/5/5a/Canis_lupus_265b.jpg/800px-Canis_lupus_265b.jpg',
            'password'=>'password',
            'type'=>'Administrador',
        ];
    }
}
