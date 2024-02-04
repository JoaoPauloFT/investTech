<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreUserRequest;
use App\Http\Requests\UpdatePasswordUserRequest;
use App\Models\Church;
use App\Models\Role;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth');
    }

    public function index(User $user) {

        $users = $user->where('deleted', 0)->get();

        return view('user.index', compact('users'));
    }

    public static function create() {
        $roles = Role::all();

        return view('user.modal.create', compact('roles'));
    }

    public static function edit(int $id) {
        $roles = Role::all();
        $user = User::find($id);

        return view('user.modal.edit', compact('user', 'roles'));
    }

    public static function edit_password(int $id) {
        $user = User::find($id);

        return view('user.modal.edit_password', compact('user'));
    }

    public function store(StoreUserRequest $request, User $user) {

        $data = $request->all();
        $data['password'] = Hash::make($request->password);
        $user->create($data);

        return redirect()->route('user');
    }

    public function destroy(int $id) {

        $user = User::find($id);

        if (!$user) {
            return back();
        }

        $user->deleted = 1;
        $user->save();

        return redirect()->route('user');
    }

    public function update(StoreUserRequest $request, string $id) {

        $user = User::find($id);

        if (!$user) {
            return back();
        }

        $user->update(
            $request->only([
                'name',
                'email',
                'role_id'
            ])
        );

        return redirect()->route('user');
    }

    public function update_password(UpdatePasswordUserRequest $request, string $id) {

        $user = User::find($id);

        if (!$user) {
            return back();
        }

        $user->password = Hash::make($request->password);
        $user->save();

        return redirect()->route('user');
    }
}
