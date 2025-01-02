<?php

namespace Tunaaoguzhann\PhpAuthApi\Controllers;

use Tunaaoguzhann\PhpAuthApi\Models\User;
use Tunaaoguzhann\PhpAuthApi\Http\Request;
use Tunaaoguzhann\PhpAuthApi\Http\Response;

class UserController
{
    private static ?User $user = null;

    private static function init(): void
    {
        if (!self::$user) {
            self::$user = new User();
        }
    }

    public static function login(Request $request): Response
    {
        self::init();

        $email = $request->post('email');
        $password = $request->post('password');

        if (!$email || !$password) {
            return Response::validationError('Email and password are required');
        }

        $userData = AuthController::validateCredentials($email, $password);
        if (!$userData) {
            return Response::unauthorized('Invalid credentials');
        }

        $token = AuthController::generateAuthToken($userData['id']);

        return Response::success([
            'token' => $token,
            'message' => 'User logged in successfully',
        ])->withHeader('Authorization', 'Bearer ' . $token);
    }

    public static function register(Request $request): Response
    {
        self::init();

        $email = $request->post('email');
        $password = $request->post('password');

        if (!$email || !$password) {
            return Response::validationError('Email and password are required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return Response::validationError('Invalid email format');
        }

        try {
            $userId = self::$user->create([
                'email' => $email,
                'password' => password_hash($password, PASSWORD_DEFAULT)
            ]);

            return Response::success([
                'message' => 'User registered successfully',
                'user_id' => $userId
            ], 201);
        } catch (\Exception $e) {
            return Response::serverError('Could not create user');
        }
    }

    public static function getProfile(Request $request): Response
    {
        self::init();
        
        $userId = AuthController::validateToken($request->bearerToken());
        if (!$userId) {
            return Response::unauthorized();
        }

        $userData = self::$user->findById($userId);
        if (!$userData) {
            return Response::notFound('User not found');
        }

        unset($userData['password']);
        return Response::success($userData);
    }
} 