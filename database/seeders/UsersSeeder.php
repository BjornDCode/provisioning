<?php

namespace Database\Seeders;

use App\Models\Account\Team;
use App\Models\Auth\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UsersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::factory()->create([
            'email' => 'test@example.com',
            'password' => Hash::make('password'),
        ]);
        $team = Team::factory()->create([
            'owner_id' => $user->id,
        ]);

        $membershipteam = Team::factory()->create();

        $membershipteam->join($user);

        $user->current_team_id = $team->id;
        $user->save();

        $invitee = User::factory()->create();
        $team->invite($invitee->email);

        $member = User::factory()->create();
        $team->join($member);

    }
}
