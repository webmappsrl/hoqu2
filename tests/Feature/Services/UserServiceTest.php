<?php

// namespace Tests\Feature\Services;

use Tests\TestCase;
use App\Models\User;
use Illuminate\Support\Str;
use App\Services\UserService;
use Illuminate\Support\Facades\Hash;

// class UserServiceTest extends TestCase
// {

//   public function setUp(): void
//   {
//     $this->service = app()->make(UserService::class);
//   }

//   /**
//    * Test createRemoteInstanceUser method
//    *
//    * @return void
//    */
//   public function test_createRemoteInstanceUser_that_create_an_user()
//   {

//     Hash::spy();
//     Str::spy();


//     $user = $this->service->createRemoteInstanceUser('192.168.1.1', 'test', '1111111111', 'http://webmapp.it/api/test');

//     Hash::shouldHaveReceived('make')->once();
//     Hash::shouldHaveReceived('random')->once();

//     $this->assertTrue($user instanceof User);
//   }
// }
