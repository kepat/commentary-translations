<?php

/**
 * Database Seeder
 *
 * @author      Kevin Tan <tankpst@gmail.com>
 * @license     http://opensource.org/licenses/MIT The MIT License (MIT)
 * @version     Release: 0.1.0
 * @link        http://github.com/vertical-software-asia/scs
 * @since       Class available since Release 0.1.0
 */
class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @access public
     * @return void
     */
    public function run()
    {
        Eloquent::unguard();

        $seeds = [
            'RolesTableSeeder',
            'UsersTableSeeder'
        ];

        foreach ($seeds as $seed) {
            $this->call($seed);
        }

        $this->command->info('Tables seeded.');
    }
}
