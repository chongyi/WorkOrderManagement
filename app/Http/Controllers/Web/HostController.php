<?php

namespace App\Http\Controllers\Web;

use App\WorkOrderManagement\User;
use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

class HostController extends Controller
{
    use BaseDataBootstrap;

    public function __construct()
    {
        parent::__construct();

        $this->middleware('auth');
    }

    public function getIndex()
    {
        return view('host.index');
    }

    public function getLogin()
    {
        return view('host.login');
    }

    public function getRegister()
    {
        return view('host.register');
    }

    public function postRegister(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email'    => 'required|unique:users|email|max:128',
            'password' => 'required|min:6',
            'name'     => 'required|min:1|max:128'
        ]);

        if ($validator->fails()) {
            return redirect()->route('host.register')->withErrors($validator->errors());
        }

        $user           = new User();
        $user->name     = $request->input('name');
        $user->email    = $request->input('email');
        $user->password = \Hash::make($request->input('password'));

        $user->save();

        return redirect()->route('host.login')->with('register.success', true);
    }

    public function postLogin(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email'    => 'required|exists:users',
            'password' => 'required'
        ]);

        if ($validator->fails()) {
            return redirect()->route('host.login')->withErrors($validator->errors());
        }

        if (\Auth::attempt($request->only(['email', 'password']), $request->input('remember', false))) {
            return redirect()->route('host.index');
        }

        return redirect()->route('host.login')->withErrors(['message' => 'fail']);
    }

    public function getLogout()
    {
        \Auth::logout();

        return redirect()->back();
    }
}
