<?php
namespace App\Models;
use Illuminate\Foundation\Auth\User as Authenticatable;
class User extends Authenticatable{
 protected $table='users';public $timestamps=false;
 protected $fillable=['username','email','password','role_id','is_active'];
 protected $hidden=['password'];
 protected function casts():array{return ['is_active'=>'boolean'];}
 public function roleName():string{return match((int)$this->role_id){1=>'Administrador',2=>'Bibliotecario',default=>'Lector'};}
 public function isAdmin():bool{return (int)$this->role_id===1;}
 public function isLibrarian():bool{return (int)$this->role_id===2;}
 public function isReader():bool{return (int)$this->role_id===3;}
}
