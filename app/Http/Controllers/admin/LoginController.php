<?php

namespace App\Http\Controllers\admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\user;

class LoginController extends Controller
{
    // this method will show admin login page

    public function index(){
        return view('admin/login');
    }

    // this method will authenticate admin
    public function authenticate(Request $request){

        $validator = Validator::make($request->all(),[
            'email' => 'required|email',
            'password' => 'required'
        ]);

        if($validator->passes()){

            if(Auth::guard('admin')->attempt(['email' => $request->email, 'password' => $request->password])){

                if(Auth::guard('admin')->user()->role != "admin"){

                    Auth::guard('admin')->logout();
                    return redirect()->route('admin.login')->with('error','You are not authorized to access this page');    

                }

                return redirect()->route('admin.dashboard');
                
            }else{
                return redirect()->route('admin.login')->with('error','Either Email or Password is Incorrect.');
            }

        }else{
            return redirect()->route('admin.login')
                ->withInput()
                ->withErrors($validator);
        }   
    }

    // this method will show register page for admin
    public function register(){
        return view('admin/register');
    }

    public function processRegister(Request $request){

        $validator = Validator::make($request->all(),[

            'name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:5|max:8',
            'password_confirmation' => 'required',

        ]);

        if($validator->passes()){
            
            $user = new User();
            $user->name = $request->name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role = 'admin';
            $user->save();

            return redirect()->route('admin.login')->with('success','You have successfully registered.');
        }else{
            return redirect()->route('admin.register')
                ->withInput()
                ->withErrors($validator);
        }   
    }

    public function logout(){
        Auth::guard('admin')->logout();
        return redirect()->route('admin.login');
    }
}
