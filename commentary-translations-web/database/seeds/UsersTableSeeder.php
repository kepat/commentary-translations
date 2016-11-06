<?php

use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
	 /**
     * Migrate
     *
     * @access public
     * @return void
     */
    public function run()
    {
        $users = [
            [
                'first_name'  => 'Indigitous',
                'last_name'   => 'Administrator',
                'username'    => 'admin',
                'email'       => 'admin@gmail.com',
                'password'    => Hash::make('password'),
                'role_id'     => 1,
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]
        ];

        DB::table('users')->insert($users);
    }
}
