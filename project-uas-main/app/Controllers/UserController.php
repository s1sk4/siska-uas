<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use App\Models\UserModel;

class UserController extends ResourceController
{
    protected $modelName = 'App\Models\UserModel';
    protected $format = 'json';

    public function index()
    {
        // Bug #12: No pagination
        $users = $this->model->paginate(10);  
        return $this->model->pager;

        foreach ($users as &$user) {
        unset($user['password']);
    }

        return $this->respond([
            'users' => $users,
            'pager' => $pager->getDetails()
        ]);
    }

    public function show($id = null)
    {
        // Bug #13: validation ID
        if (!is_numeric($id) || $id <= 0) {
            return $this->failValidationErrors('Invalid ID');
        }
        $user = $this->model->find($id);

        if (!$user) {
            return $this->failNotFound('User not found');
        }

        // Bug #14: Returning sensitive data
        unset($user['password']);
        return $this->respond($user);
    }

    public function update($id = null)
    {
        // Bug #15: No authorization check (user can update other users)
        $token = $this->request->getHeaderLine('Authorization');
        $token = str_replace('Bearer ', '', $token);
        $decoded = $this->jwt->decode($token);

        if ($decoded->user_id != $id) {
            return $this->failNotFound('User not found');
        }

        // Bug #16: No input validation
        if ($this->model->update($id, $data)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'User updated successfully'
            ]);
        }

        return $this->failServerError('Update failed');
    }

    public function delete($id = null)
    {
        // Bug #17: No authorization check
        if (!$this->model->find($id)) {
            return $this->failNotFound('User not found');
        }

        if ($this->model->delete($id)) {
            return $this->respond([
                'status' => 'success',
                'message' => 'User deleted successfully'
            ]);
        }

        return $this->failServerError('Delete failed');
    }
}
