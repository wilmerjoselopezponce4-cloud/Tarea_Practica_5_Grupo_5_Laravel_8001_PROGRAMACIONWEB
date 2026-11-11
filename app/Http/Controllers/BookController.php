<?php
namespace App\Http\Controllers;
use App\Models\{Book,Loan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
class BookController{
 private function fields(Request $r):array{return $r->validate([
  'title'=>'required|string|min:2|max:160','author'=>'required|string|min:2|max:100',
  'year'=>'nullable|integer|min:1450|max:'.(date('Y')+1),
  'genre'=>'required|string|min:2|max:60','copies_total'=>'required|integer|min:0|max:9999',
  'description'=>'nullable|string|max:2000','isbn'=>'nullable|string|max:24',
  'cover_tone'=>'required|integer|min:1|max:6',
 ]);}
 public function index(Request $r){
   $query=Book::query()->when(!$r->user()->isLibrarian(),fn($q)=>$q->where('is_active',1));
   if($search=trim((string)$r->query('q',''))) $query->where(fn($q)=>$q->where('title','like','%'.$search.'%')->orWhere('author','like','%'.$search.'%'));
   if($genre=$r->query('genre'))$query->where('genre',$genre);
   $books=$query->orderBy('title')->get();
   $genres=Book::where('is_active',1)->distinct()->orderBy('genre')->pluck('genre');
   $activeIds=Loan::where('user_id',$r->user()->id)->whereIn('status',['Solicitado','Prestado','Devolucion solicitada'])->pluck('book_id');
   return view('books.index',compact('books','genres','activeIds'));
 }
 public function store(Request $r){$d=$this->fields($r);$d['quantity']=$d['copies_total'];Book::create($d);return back()->with('ok','Libro agregado.');}
 public function update(Request $r,Book $book){$d=$this->fields($r);
   DB::transaction(function()use($book,$d){
     $locked=Book::whereKey($book->id)->lockForUpdate()->firstOrFail();
     $active=Loan::where('book_id',$locked->id)->whereIn('status',['Prestado','Devolucion solicitada'])->count();
     if($d['copies_total']<$active)throw \Illuminate\Validation\ValidationException::withMessages(['copies_total'=>'El número total debe cubrir los '.$active.' ejemplares prestados.']);
     $locked->update([...$d,'quantity'=>$d['copies_total']-$active]);
   });return back()->with('ok','Libro actualizado.');
 }
 public function destroy(Book $book){DB::transaction(function()use($book){
    $locked=Book::whereKey($book->id)->lockForUpdate()->firstOrFail();
    if(Loan::where('book_id',$locked->id)->exists())$locked->update(['is_active'=>false]);else $locked->delete();
   });return back()->with('ok','Libro retirado del catálogo.');
 }
 public function restore(Book $book){$book->update(['is_active'=>true]);return back()->with('ok','Libro restaurado.');}
}
