<?php

namespace App\Services;

use App\Models\Place;
use App\Models\RoleUser;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserService
{
    const LIMIT = 20;

    public function getFoster($data)
    {
        $page = (isset($data['page']) && $data['page'] >= 1) ? $data['page'] : 1;

        $foster = User::offset(($page - 1) * self::LIMIT);

        //search
        $foster = $this->filterFoster($foster, $data);
        $foster = $foster->limit(self::LIMIT)->get();

        return $foster;
    }

    public function verifyUser($email, $googleId)
    {
        $user = User::where('email', $email)->first();
        if ($user && $user->google_id === $googleId) {
            return $user;
        } elseif ($user && $user->google_id == null) {
            $user->google_id = $googleId;
            $user->save();

            return $user;
        } else {
            return false;
        }
    }

    private function filterFoster($foster, $data)
    {
        if (isset($data['name']) && $data['name'] !== '') {
            $foster->where('name', 'like', '%'.$data['name'].'%');
        }
        if (isset($data['director_name']) && $data['director_name'] !== '') {
            $foster->where('director_name', 'like', '%'.$data['director_name'].'%');
        }
        if (isset($data['phone']) && $data['phone'] !== '') {
            $foster->where('phone', 'like', '%'.$data['phone'].'%');
        }
        if (isset($data['address']) && $data['address'] !== '') {
            $foster->where('address', 'like', '%'.$data['address'].'%');
        }

        return $foster;
    }

    public function getTotalFoster($data)
    {
        $foster = User::where('id', '!=', 'null');
        $foster = $this->filterFoster($foster, $data);

        return $foster->count();
    }

    public function createUser($data)
    {
        $user = User::create([
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'email'   => $data['email'],
            'password'   => Hash::make($data['password']),
            "note"    => $data['note'] ?? '',
        ]);

        $user->roles()->attach($data['roles']);
    }

    public function getUsers($data)
    {
        $page = (isset($data['page']) && $data['page'] >= 1) ? $data['page'] : 1;
        $users = User::offset(($page - 1) * self::LIMIT);

        //search
        $users = $this->filterUsers($users, $data);
        $users = $users->with('roles')->limit(self::LIMIT)->get();

        return $users;
    }

    public function getAllUsers()
    {
        return User::all();
    }

    private function filterUsers($users, $data)
    {

        if (isset($data['type']) && $data['type'] == User::FOSTER) {
            $roleIds = User::FOSTER_IDS;
        } elseif (isset($data['type']) && $data['type'] == User::MEDICAL) {
            $roleIds = User::MEDICAL_IDS;
        } elseif (isset($data['type']) && $data['type'] == User::VOLUNTEER) {
            $roleIds = User::VOLUNTEER_IDS;
        } else {
            $roleIds = null;
        }

        if ($roleIds) {
            $users = User::whereHas('roles', function ($query) use ($roleIds) {
                $query->whereIn('role_id', $roleIds);
            });
        }

        if (isset($data['name']) && $data['name'] !== '') {
            $users->where('name', 'like', '%'.$data['name'].'%');
        }
        if (isset($data['phone']) && $data['phone'] !== '') {
            $users->where('phone', 'like', '%'.$data['phone'].'%');
        }
        if (isset($data['address']) && $data['address'] !== '') {
            $users->where('address', 'like', '%'.$data['address'].'%');
        }

        return $users;
    }

    public function getTotalUsers($data)
    {
        $places = User::where('id', '!=', null)->get();
        $places = $this->filterUsers($places, $data);

        return $places->count();
    }

    public function getUserById($userId)
    {
        return User::with('roles')->find($userId);
    }

    public function deleteById($userId)
    {
        $user = User::with(['animals', 'roles', 'animalCreated'])->find($userId);
        if (count($user->animals) > 0) {
            return 'Đang có Case ở nhà chủ tài khoản này, nên ko thể xóa';
        }
        if (count($user->animalCreated) > 0) {
            return 'Tài khoản này đã từng tạo case, nên không thể xóa';
        } else {
            $user->delete();

            return true;
        }
    }

    public function updateUser($data, $userId)
    {
        $user = User::find($userId);

        $dataUpdate = [
            'name'    => $data['name'],
            'phone'   => $data['phone'] ?? '',
            'address' => $data['address'] ?? '',
            'email'   => $data['email'],
            "note"    => $data['note'] ?? '',
        ];
        if($data['password'] && strlen($data['password'])) {
            $dataUpdate['password'] = Hash::make($data['password']);
        }
        $user->update($dataUpdate);

        RoleUser::where('user_id', $userId)->delete();
        $user->roles()->attach($data['roles']);
    }
}
