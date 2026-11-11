<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
class Loan extends Model {
 protected $table='transactions';public $timestamps=false;
 protected $fillable=['user_id','book_id','date_of_issue','date_of_return','due_date','status','return_requested_at','processed_by'];
 public function book(){return $this->belongsTo(Book::class,'book_id');}
 public function reader(){return $this->belongsTo(User::class,'user_id');}
 public function processor(){return $this->belongsTo(User::class,'processed_by');}
}
