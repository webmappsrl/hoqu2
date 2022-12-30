<?php

namespace App\Services;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;


/**
 * Services about User and its tokens
 */
class UserService
{

  /**
   * Create an user for a remote instance in the users database table
   *
   * @param string $ip
   * @param string $role
   * @param string $api_token
   * @param string $endpoint
   * @return array
   */
  public function createInstanceUser($ip, $hoqu_roles, $hoqu_api_token = '', $endpoint = '', $hoqu_processor_capabilities = [])
  {

    //TODO? validate input like ip

    $password = Hash::make(Str::random(100)); //generate a random password with 100 characters
    $name = $this->getAvailableUserNameByIp($ip);
    $email = "geouser+$name@webmapp.it";

    $newUserFill = [
      'email' => $email,
      'name' => $name,
      'email_verified_at' => now(),
      'password' => $password,
      'hoqu_api_token' => $hoqu_api_token,
      'endpoint' => $endpoint,
      'hoqu_roles' => $hoqu_roles,
      'hoqu_processor_capabilities' => $hoqu_processor_capabilities
    ];

    $user = User::firstOrCreate($newUserFill);


    //TODO: which abilities by default?
    $token = $user->createToken($ip)->plainTextToken;


    return ['user' => $user, 'token' => $token];
  }


  /**
   * Create a register user or only update the password if exists
   *
   * @param string $password
   * @return \App\Models\User;
   */
  public function createRegisterUser($password)
  {
    $email = 'register@webmapp.it';
    $passwordHash = Hash::make($password);

    $userQuery = User::where('email', $email);
    if ($userQuery->count() > 0) {
      $user = $userQuery->first();
      $user->password = $passwordHash;
      $user->save();
      //remove all previous tokens if user exists
      $user->tokens()->delete();
    } else {
      $user = User::create([
        'name' => 'register',
        'email' => $email,
        'password' => $passwordHash,
        'email_verified_at' => now(),
        'hoqu_roles' => ['register']
      ]);
    }

    return $user;
  }

  /**
   * Return an unique name for user based on ip
   *
   * performs a query loop inside user table untill an unique name is found
   *
   * @param string $ip
   * @return string
   */
  protected function getAvailableUserNameByIp($ip)
  {
    $ok = false;

    $name = $ip;
    $counter = 1;
    while (!$ok) {
      $check = User::where('name', $name);
      if ($check->count() === 0) {
        $ok = true;
      } else {
        $name = $ip . '_' . $counter;
        $counter++;
      }
    }

    return $name;
  }
}
