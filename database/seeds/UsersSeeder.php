<?php

use App\User;
use Illuminate\Database\Seeder;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds
     *
     * @return void
     */
    public function run()
    {
        $this->command->getOutput()->writeln('Seed users...');

        // delete all users
        $users = User::all();

        foreach ($users as $user)
            $user->delete();

        $users = [
            [
                'name'     => 'user1',
                'email'    => 'user1@gmail.com',
                'password' => bcrypt('123'),
            ],
            [
                'name'     => 'user2',
                'email'    => 'user2@gmail.com',
                'password' => bcrypt('123'),
            ],
        ];

        foreach ($users as $user)
            User::create($user);
    }
}
