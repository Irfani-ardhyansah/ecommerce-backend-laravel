<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\UserDetail;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        User::create([
            'email'     => 'admin@email.com',
            'password'  => bcrypt('qweasd123'),
            'role'      => 'admin'
        ]);

        $user = User::create([
            'email'     => 'user@email.com',
            'password'  => bcrypt('qweasd123'),
            'role'      => 'customer'
        ]);

        UserDetail::create([
            'user_id'   => $user->id,
            'name'      => 'Irfani Ardhyan',
            'address'   => 'Surabaya',
            'phone'     => '081237412'
        ]);
    }
}
