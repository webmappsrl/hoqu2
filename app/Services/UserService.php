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
   * @return object
   */
  public function createInstanceUser($ip, $role, $api_token, $endpoint)
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
      'api_token' => $api_token,
      'endpoint' => $endpoint,
      'is_processor' => $role == 'processor',
      'is_caller' => $role == 'caller'
    ];

    $user = User::firstOrCreate($newUserFill);


    //TODO: which abilities by default?
    $token = $user->createToken($ip)->plainTextToken;


    return ['user' => $user, 'token' => $token];
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
