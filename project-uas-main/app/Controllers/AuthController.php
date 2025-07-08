<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;
use App\Libraries\JWTLibrary;

class AuthController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';
    protected $jwt;

    public function __construct()
    {
        $this->jwt = new JWTLibrary();
    }

    public function register()
    {
        $data = $this->request->getPost();

        // Bug #6: No input validation / Menambahkan input validasi
        if (empty($data['name']) || empty($data['email']) || empty($data['password'])) {
            return $this->failValidationErrors('All fields are required');
        }
        $userModel = new UserModel();

        // Bug #7: Password not hashed
        $hashedPassword = password_hash($data['password'], PASSWORD_DEFAULT); # Menambahkan hash password
        $userData = [
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => $data['password']
        ];

        $userId = $userModel->insert($userData);

        if ($userId) {
            unset($userData['password']);   //hapus respon hanya kirim data lain
            return $this->respond([
                'status' => 'success',
                'message' => 'User registered successfully',
                'data' => $userData // Bug #8: Returning password in response
            ]);
        }

        return $this->failServerError('Registration failed');
    }

    public function login()
    {
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');

        // Bug #9: No input validation
        if (empty($email) || empty($password)) {    #validasi email dan password
        return $this->failValidationErrors('Email and password are required');
        }
        $userModel = new UserModel();
        $user = $userModel->where('email', $email)->first();

        // Bug #10: Plain text password comparison
        if ($user && password_verify($password, $user['password'])) {   #verifikasi password
            $payload = [
                'user_id' => $user['id'],
                'email' => $user['email'],
                'exp' => time() + 3600
            ];

            $token = $this->jwt->encode($payload);

            return $this->respond([
                'status' => 'success',
                'token' => $token,
                'user' => $user
            ]);
        }

        return $this->failUnauthorized('Invalid credentials');
    }

    public function refresh()
    {
        // Bug #11: Missing implementation
        $token = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);
        if (!$token) {
            return $this->failUnauthorized('Token required');
        }
        try {
            $decoded = $this->jwt->decode($token);

            if ($decoded->exp < time()) {
        return $this->respond(['message' => 'Not implemented']);
    }
           $newPayload = [
                'user_id' => $decoded->user_id,
                'email' => $decoded->email,
                'exp' => time() + 3600
            ];
            $newToken = $this->jwt->encode($newPayload);
                return $this->respond([
                'status' => 'success',
                'token' => $newToken
            ]);
         } catch (\Exception $e) {
            return $this->failUnauthorized('Invalid token');
         }
    }
}
