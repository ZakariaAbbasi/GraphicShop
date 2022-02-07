<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Utilities\DiePages;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Http\Requests\Admin\Uers\StoreRequest;
use App\Http\Requests\Admin\Uers\UpdateRequest;

class UsersController extends Controller
{
    public function create()
    {
        return view('admin.users.add');
    }

    public function delete($id)
    {
        $user = User::where('id', $id)->delete();
        if (!$user)
            return DiePages::messages('failed', 'خطا در حذف کابر');

        return DiePages::messages('success', 'کاربر حذف شد');
    }
    
    public function edit($id)
    {
        $user = User::findOrFail($id);
        return view('admin.users.edit', compact('user'));
    }

    public function update(UpdateRequest $request, $id)
    {
        $validateData = $request->validated();
        $data = null;
        $userId = User::findOrFail($id);

        if (isset($validateData['password'])) {
            $data = Hash::make($validateData['password']);
        }
        if ($this->validatedPass($validateData['password'], $validateData['passwordRepeat']))
            return DiePages::messages('faild', 'دوتا رمز عبور باهم یکسان نیست');


        $user = $userId->update(
            [
                'name' => $validateData['fullName'],
                'email' => $validateData['email'],
                'mobile' => $validateData['mobile'],
                'role' => $validateData['role'],
                'password' => $data
            ]
        );
        if (!$user) {

            return DiePages::messages('failed', 'کاربر بروزرسانی نشد');
        }

        return DiePages::messages('success', 'کاربر بروزرسانی شد');
    }

    public function all()
    {
        $users = User::paginate(1);
        return view('admin.users.all', compact('users'));
    }

    public function store(StoreRequest $request)
    {
        $validateData = $request->validated();

        if ($this->validatedPass($validateData['password'], $validateData['passwordRepeat']))
            return DiePages::messages('faild', 'دوتا رمز عبور باهم یکسان نیست');



        $user = User::create(
            [
                'name' => $validateData['fullName'],
                'email' => $validateData['email'],
                'mobile' => $validateData['mobile'],
                'role' => $validateData['role'],
                'password' =>  Hash::make($validateData['password']),

            ]
        );
        if (!$user)
            return DiePages::messages('failed', 'کاربرایجاد نشد');

        return DiePages::messages('success', 'کاربر ایجاد شد');
    }

    private  function validatedPass($password, $passwordRepeat)
    {
        if ($password != $passwordRepeat) {
            return true;
        }
        return false;
    }
}
