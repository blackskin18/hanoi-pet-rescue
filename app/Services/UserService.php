<?php

namespace App\Services;

use App\Models\User;

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

    public function verifyUser($email, $googleId) {
        $user = User::where('email', $email)->first();
        if($user && $user->google_id === $googleId) {
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
            $foster->where('name', 'like', '%' . $data['name'] . '%');
        }
        if (isset($data['director_name']) && $data['director_name'] !== '') {
            $foster->where('director_name', 'like', '%' . $data['director_name'] . '%');
        }
        if (isset($data['phone']) && $data['phone'] !== '') {
            $foster->where('phone', 'like', '%' . $data['phone'] . '%');
        }
        if (isset($data['address']) && $data['address'] !== '') {
            $foster->where('address', 'like', '%' . $data['address'] . '%');
        }
        return $foster;
    }

    public function getTotalFoster($data)
    {
        $foster = User::where('id', '!=', 'null');
        $foster = $this->filterFoster($foster, $data);

        return $foster->count();
    }
}
