<?php

use Laracasts\TestDummy\Factory as TestDummy;

class UserRepositoryTest extends RepositoryTestCase
{

    public function repository()
    {
        return 'App\Repositories\UserRepository';
    }


    public function create()
    {
        return TestDummy::attributesFor('App\User');
    }


    public function update()
    {
        return ['full_name' => 'New Full Name'];
    }
}
