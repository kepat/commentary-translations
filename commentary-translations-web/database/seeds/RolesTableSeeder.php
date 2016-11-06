<?php

/**
 * Roles Table Seeder
 *
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class RolesTableSeeder extends Seeder
{

    /**
     * Migrate
     *
     * @access public
     * @return void
     */
    public function run()
    {
        $roles = [
            [
                'name'        => 'Administrator',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'name'        => 'Translator',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ],
            [
                'name'        => 'Reader',
                'created_at'  => date('Y-m-d H:i:s'),
                'updated_at'  => date('Y-m-d H:i:s')
            ]
        ];

        DB::table('roles')->insert($roles);
    }
}
