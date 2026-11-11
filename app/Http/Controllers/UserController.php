<?php
namespace App\Http\Controllers;
use App\Models\{User,Loan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\Rule;
use Illuminate\Validation\ValidationException;
class UserController{
 public function index(){return view('users.index',['users'=>User::orderBy('role_id')->orderBy('username')->get()]);}
 private function input(Request $r,?User $target=null):array{return $r->validate([
  'username'=>'required|string|min:3|max:50',
  'email'=>['required','email','max:100',Rule::unique('users','email')->ignore($target?->id)],
  'role_id'=>['required','integer',Rule::in([1,2,3])],
  'password'=>$target?'nullable|string|min:10|max:72':'required|string|min:10|max:72'
 ]);}
 public function store(Request $r){$d=$this->input($r);$d['password']=Hash::make($d['password']);User::create($d);return back()->with('ok','Usuario creado.');}
 public function update(Request $r,User $user){$d=$this->input($r,$user);
   if($r->user()->id===$user->id && (int)$d['role_id']!==1)$this->fail('No puedes quitarte el rol administrador.');
   if($user->isAdmin() && (int)$d['role_id']!==1 && User::where('role_id',1)->where('is_active',1)->count()<=1)$this->fail('Debe quedar un administrador activo.');
   $user->update(['username'=>$d['username'],'email'=>$d['email'],'role_id'=>$d['role_id'],...(!empty($d['password'])?['password'=>Hash::make($d['password'])]:[])]);
   return back()->with('ok','Usuario actualizado.');
 }
 private function fail(string $m):never{throw ValidationException::withMessages(['usuario'=>$m]);}
 public function destroy(Request $r,User $user){
   if($user->id===$r->user()->id)$this->fail('No puedes eliminar tu propia cuenta.');
   if($user->isAdmin() && User::where('role_id',1)->where('is_active',1)->count()<=1)$this->fail('Debe quedar un administrador activo.');
   DB::transaction(function()use($user){
     $hasHistory=Loan::where('user_id',$user->id)->orWhere('processed_by',$user->id)->exists();
     if($hasHistory)$user->update(['is_active'=>false]);else $user->delete();
   });return back()->with('ok','Cuenta retirada. Se conserva el historial cuando corresponde.');
 }
 public function restore(User $user){$user->update(['is_active'=>true]);return back()->with('ok','Usuario activado.');}
}
