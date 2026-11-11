<?php
namespace App\Http\Controllers;
use App\Models\{Book,Loan};
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;
class LoanController{
 private const ACTIVE=['Solicitado','Prestado','Devolucion solicitada'];
 private function fail(string $message):never{throw ValidationException::withMessages(['prestamo'=>$message]);}
 public function mine(Request $r){
  $history=$r->query('tab')==='history';
  $loans=Loan::with('book')->where('user_id',$r->user()->id)
    ->whereIn('status',$history?['Devuelto','Rechazado','Cancelado']:self::ACTIVE)
    ->orderByDesc('id')->get();
  $active=Loan::where('user_id',$r->user()->id)->whereIn('status',self::ACTIVE)->count();
  $done=Loan::where('user_id',$r->user()->id)->whereIn('status',['Devuelto','Rechazado','Cancelado'])->count();
  return view('loans.mine',compact('loans','active','done','history'));
 }
 public function request(Request $r,Book $book){$user=$r->user();
  DB::transaction(function()use($book,$user){
    DB::table('users')->where('id',$user->id)->lockForUpdate()->first();
    $b=Book::whereKey($book->id)->lockForUpdate()->firstOrFail();
    if(!$b->is_active || $b->quantity<1)$this->fail('Este libro no está disponible.');
    if(Loan::where('user_id',$user->id)->where('book_id',$b->id)->whereIn('status',self::ACTIVE)->exists())$this->fail('Ya tienes una solicitud o préstamo activo de este libro.');
    if(Loan::where('user_id',$user->id)->whereIn('status',self::ACTIVE)->count()>=5)$this->fail('Solo puedes tener cinco solicitudes o préstamos activos.');
    Loan::create(['user_id'=>$user->id,'book_id'=>$b->id,'status'=>'Solicitado']);
  });return back()->with('ok','Solicitud enviada al bibliotecario.');
 }
 public function cancel(Request $r,Loan $loan){
   $changed=Loan::whereKey($loan->id)->where('user_id',$r->user()->id)->where('status','Solicitado')->update(['status'=>'Cancelado']);
   if(!$changed)$this->fail('La solicitud no puede cancelarse.');return back()->with('ok','Solicitud cancelada.');
 }
 public function returnRequest(Request $r,Loan $loan){
    $changed=Loan::whereKey($loan->id)->where('user_id',$r->user()->id)->where('status','Prestado')->update(['status'=>'Devolucion solicitada','return_requested_at'=>now()]);
    if(!$changed)$this->fail('El préstamo no está disponible para devolución.');return back()->with('ok','Solicitud de devolución enviada.');
 }
 public function circulation(Request $r){
   $tab=in_array($r->query('tab'),['pending','active','history','all'],true)?$r->query('tab'):'pending';
   $q=Loan::with(['book','reader'])->orderByDesc('id');
   if($tab==='pending')$q->where('status','Solicitado');
   if($tab==='active')$q->whereIn('status',['Prestado','Devolucion solicitada']);
   if($tab==='history')$q->whereIn('status',['Devuelto','Rechazado','Cancelado']);
   $loans=$q->get();
   $counts=[
    'pending'=>Loan::where('status','Solicitado')->count(),
    'active'=>Loan::whereIn('status',['Prestado','Devolucion solicitada'])->count(),
    'history'=>Loan::whereIn('status',['Devuelto','Rechazado','Cancelado'])->count(),
    'all'=>Loan::count()
   ];return view('loans.circulation',compact('loans','counts','tab'));
 }
 public function approve(Request $r,Loan $loan){
   DB::transaction(function()use($r,$loan){
     $locked=Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
     if($locked->status!=='Solicitado')$this->fail('La solicitud ya fue procesada.');
     // Lock por lector para evitar dos aprobaciones concurrentes que superen el límite.
     DB::table('users')->where('id',$locked->user_id)->lockForUpdate()->first();
     if(Loan::where('user_id',$locked->user_id)->whereIn('status',['Prestado','Devolucion solicitada'])->count()>=3)$this->fail('El lector ya tiene tres préstamos activos.');
     $book=Book::whereKey($locked->book_id)->lockForUpdate()->firstOrFail();
     if(!$book->is_active||$book->quantity<1)$this->fail('No quedan ejemplares disponibles.');
     $book->decrement('quantity');
     $locked->update(['status'=>'Prestado','date_of_issue'=>today()->toDateString(),'due_date'=>today()->addDays(14)->toDateString(),'processed_by'=>$r->user()->id]);
   });return back()->with('ok','Préstamo aprobado.');
 }
 public function reject(Request $r,Loan $loan){
   $changed=Loan::whereKey($loan->id)->where('status','Solicitado')->update(['status'=>'Rechazado','processed_by'=>$r->user()->id]);
   if(!$changed)$this->fail('La solicitud ya fue procesada.');return back()->with('ok','Solicitud rechazada.');
 }
 public function complete(Request $r,Loan $loan){
  DB::transaction(function()use($r,$loan){
    $locked=Loan::whereKey($loan->id)->lockForUpdate()->firstOrFail();
    if(!in_array($locked->status,['Prestado','Devolucion solicitada'],true))$this->fail('Este préstamo no puede cerrarse.');
    $book=Book::whereKey($locked->book_id)->lockForUpdate()->firstOrFail();
    $locked->update(['status'=>'Devuelto','date_of_return'=>today()->toDateString(),'processed_by'=>$r->user()->id]);
    $book->increment('quantity');
  });return back()->with('ok','Devolución confirmada. Ejemplar disponible nuevamente.');
 }
}
