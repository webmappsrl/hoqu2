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
   * @param string $password - the plain text password
   * @param array $hoqu_roles
   * @param string $hoqu_api_token
   * @param string $endpoint
   * @param array $hoqu_processor_capabilities
   * @return array
   */
  public function createInstanceUser($ip, $password, $hoqu_roles, $hoqu_api_token = '', $endpoint = '', $hoqu_processor_capabilities = [])
  {

    //TODO? validate input like ip

    $name = $this->getAvailableUserNameByIp($ip);
    $email = "geouser+$name@webmapp.it";

    $newUserFill = [
      'email' => $email,
      'name' => $name,
      'email_verified_at' => now(),
      'password' => Hash::make($password),
      'hoqu_api_token' => $hoqu_api_token,
      'endpoint' => $endpoint,
      'hoqu_roles' => $hoqu_roles,
      'hoqu_processor_capabilities' => $this->getCorrectCapabilitiesByRoles($hoqu_roles, $hoqu_processor_capabilities)
    ];

    $user = User::create($newUserFill);


    //TODO: which abilities by default?
    $token = $user->createToken($ip)->plainTextToken;


    return ['user' => $user, 'token' => $token];
  }

  /**
   * Check capabilities over roles
   *
   * @param array $hoqu_roles
   * @param array $hoqu_processor_capabilities
   * @return array
   */
  public function getCorrectCapabilitiesByRoles($hoqu_roles, $hoqu_processor_capabilities)
  {
    //TODO: enum for roles
    //process capabilities over role
    if (!in_array('processor', $hoqu_roles) || (count($hoqu_roles) == 1 && in_array('caller', $hoqu_roles))) {
      $hoqu_processor_capabilities = [];
    }
    return $hoqu_processor_capabilities;
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


  /**
   * Undocumented function
   *
   * @param [type] $job
   * @return void
   */
  public function getProcessorByJob($job)
  {

    //TODO: to test
    return User::whereJsonContains('hoqu_processor_capabilites', 'processor')
      ->whereJsonContains('hoqu_roles', $job)
      ->withCount('hoquJobs')
      ->get();
  }
}
