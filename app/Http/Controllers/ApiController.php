<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\HoquJob;
use Illuminate\Http\Request;
use App\Services\UserService;
use Wm\WmPackage\Enums\JobStatus;
use Illuminate\Support\Facades\Hash;

/**
 * ApiController class
 *
 * The main api routes controller
 */
class ApiController extends Controller
{

    /**
     * Store a new HoquJob with a LaravelJob.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //must have: name, input

        //TODO: validate?


        $body = $request->json();
        $inputAsString = json_encode($body->get('input'));


        // HOQU JOB CREATION
        $hoquJob = HoquJob::create([
            'status' => JobStatus::New,
            'input' => $inputAsString,
            'name' => $body->get('name'),
            'caller_id' => $request->user()->id
        ]);

        // LARAVEL JOB CREATION
        $hoquJob->addStoreJob($body->get('name'), $body->get('input'));

        return response(['message' => 'created', 'job_id' => $hoquJob->id]);
    }


    /**
     * Receive done job from processor
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function done(Request $request)
    {

        $body = $request->json();

        //retrieve job_id;

        //TODO: update HoquJob ouput
        //TODO: update HoquJob status
        $hoquJob = HoquJob::where('...');

        // LARAVEL JOB CREATION
        $hoquJob->addDoneJob($body->get('output'));

        return response(['message' => 'done', 'job_id' => $hoquJob->id]);
    }

    public function register(Request $request, UserService $userService)
    {
        $fields = $request->validate([
            'password' => 'required|string',
            'hoqu_roles' => 'required|array',
            'endpoint' => 'required|string',
            'hoqu_api_token' => 'required|string',
            'hoqu_processor_capabilities' => 'array'
        ]);

        $arr = $userService->createInstanceUser(
            $request->ip(),
            $fields['password'], //plain text password
            $fields['hoqu_roles'],
            $fields['hoqu_api_token'], // hoku_api_token
            $fields['endpoint'],
            $fields['hoqu_processor_capabilities'] ?? []
        );

        $response = [
            'user' => $arr['user'],
            'token' => $arr['token'],
        ];

        return response($response, 201);
    }


    public function registerLogin(Request $request)
    {
        $fields = $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        // Check email
        $user = User::where('email', $fields['email'])
            ->whereJsonContains('hoqu_roles', 'register')
            ->first();

        // Check password
        if (!$user || !Hash::check($fields['password'], $user->password)) {
            return response([
                'message' => 'Bad creds',
            ], 401);
        }

        //register a new token with specific abilities
        $token = $user->createToken('register', ['register-users'])->plainTextToken;

        $response = [
            'user' => $user,
            'token' => $token,
        ];

        return response($response, 201);
    }
}
