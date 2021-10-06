<?php

namespace App\Actions\Fortify;

use App\Models\Team;
use App\Models\User;
use App\Models\UserRole;
use App\Models\CreditPoints;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Laravel\Fortify\Contracts\CreatesNewUsers;

class CreateNewUser implements CreatesNewUsers
{
    use PasswordValidationRules;

    /**
     * Create a newly registered user.
     *
     * @param  array  $input
     * @return \App\Models\User
     */
    public function create(array $input)
    {
        Validator::make($input, [
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users'],
            'contact' => ['max:20'],
            'user_type' => ['required'],
            'password' => $this->passwordRules(),
        ],
        [
            'name.required' =>'Please enter your name',
            'email.required' =>'Please enter your email',
            'user_type.required' =>'Please select(you want to register as?)'
        ])->validate();
        
        return DB::transaction(function () use ($input) {
            return tap(User::create([
                'name' => $input['name'],
                'email' => $input['email'],
                'contact' => isset($input['contact']) ? $input['contact'] : NULL,
                'user_type' => $input['user_type'],
                'password' => Hash::make($input['password']),
            ]),
             function (User $user) {
                $this->createTeam($user);
                $this->addUserRole($user);
            });
        });
    }

    /**
     * Create a personal team for the user.
     *
     * @param  \App\Models\User  $user
     * @return void
     */
    protected function createTeam(User $user)
    {
        $user->ownedTeams()->save(Team::forceCreate([
            'user_id' => $user->id,
            'name' => explode(' ', $user->name, 2)[0]."'s Team",
            'personal_team' => true,
        ]));
    }

    protected function addUserRole(User $user)
    {
        $addRole = new UserRole;
        $addRole->user_type = $user->user_type;
        $addRole->ur_id = $user->id;
        $addRole->save();
    }
    
}
