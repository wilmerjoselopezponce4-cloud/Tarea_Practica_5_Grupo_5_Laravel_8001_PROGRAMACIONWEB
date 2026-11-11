<?php
namespace App\Http\Controllers;
use App\Models\{Book,Loan,User};
use Illuminate\Http\Request;
class DashboardController{
 public function index(Request $r){
  $u=$r->user();$books=Book::where('is_active',1)->count();
  $available=Book::where('is_active',1)->sum('quantity');
  $pending=Loan::where('status','Solicitado')->count();
  $borrowed=Loan::whereIn('status',['Prestado','Devolucion solicitada'])->count();
  $recent=Loan::with(['book','reader'])->when($u->isReader(),fn($q)=>$q->where('user_id',$u->id))->orderByDesc('id')->limit(6)->get();
  return view('dashboard',compact('books','available','pending','borrowed','recent'));
 }
}
