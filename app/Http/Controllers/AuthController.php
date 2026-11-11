<?php
namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
class AuthController{
 public function showLogin(){return view('auth.login');}
 public function login(Request $r){
   $data=$r->validate(['email'=>'required|email','password'=>'required|string']);
   if(!Auth::attempt(['email'=>mb_strtolower($data['email']),'password'=>$data['password'],'is_active'=>1])){
     return back()->withErrors(['email'=>'Correo o contraseña incorrectos.'])->onlyInput('email');
   }
   $r->session()->regenerate();return redirect()->intended(route('dashboard'));
 }
 public function logout(Request $r){Auth::logout();$r->session()->invalidate();$r->session()->regenerateToken();return redirect()->route('login');}
 public function profile(){return view('profile');}
 public function update(Request $r){
  $u=$r->user();$data=$r->validate([
   'username'=>'required|string|min:3|max:50',
   'email'=>['required','email','max:100',Rule::unique('users','email')->ignore($u->id)],
   'current_password'=>'nullable|string','new_password'=>'nullable|string|min:10|max:72'
  ]);
  if(!empty($data['new_password'])){
    if(!Hash::check($data['current_password']??'', $u->password))return back()->withErrors(['current_password'=>'La contraseña actual no es correcta.']);
    $u->password=Hash::make($data['new_password']);
  }
  $u->username=$data['username'];$u->email=mb_strtolower($data['email']);$u->save();
  return back()->with('ok','Perfil guardado.');
 }
}
