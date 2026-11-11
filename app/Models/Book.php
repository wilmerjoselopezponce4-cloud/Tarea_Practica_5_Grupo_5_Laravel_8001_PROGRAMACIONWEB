<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Book extends Model{
 public $timestamps=false;protected $fillable=['title','author','year','genre','quantity','copies_total','description','isbn','cover_tone','is_active'];
 protected function casts():array{return ['is_active'=>'boolean'];}
 public function transactions(){return $this->hasMany(Loan::class,'book_id');}
}
