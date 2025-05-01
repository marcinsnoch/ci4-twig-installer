<?php

namespace App\Controllers;

use App\Models\UserModel;

class ProfileController extends BaseController
{
    public function index()
    {
        $user = UserModel::findOrfail(1); //$user = UserModel::find(session()->id);

        $this->twig->display('profile/index', compact('user'));
    }

    public function update()
    {
        $user = UserModel::findOrfail(1); //$user = UserModel::find(session()->id);
        $user->first_name = $this->request->getPost('first_name');
        $user->last_name = $this->request->getPost('last_name');
        if ($this->request->getPost('password') != null && $this->validate('update_password')) {
            $user->password = password_hash($this->request->getPost('password'), PASSWORD_DEFAULT);
            $this->validation->reset();
        }
        if ($this->validate('update_profile')) {
            $user->save();
            alertSuccess(lang('UserProfile.alert.profile_updated'));

            return redirect()->to('user-profile');
        }

        return redirect()->back()->withInput();
    }
}
