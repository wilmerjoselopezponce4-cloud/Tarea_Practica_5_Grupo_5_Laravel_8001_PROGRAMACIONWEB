<?php
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BookController;
use App\Http\Controllers\LoanController;
use App\Http\Controllers\UserController;
Route::get('/',fn()=>redirect()->route('dashboard'));
Route::middleware('guest')->group(function(){
  Route::get('/login',[AuthController::class,'showLogin'])->name('login');
  Route::post('/login',[AuthController::class,'login'])->middleware('throttle:8,10')->name('login.submit');
});
Route::middleware('auth')->group(function(){
  Route::get('/panel',[DashboardController::class,'index'])->name('dashboard');
  Route::post('/logout',[AuthController::class,'logout'])->name('logout');
  Route::get('/perfil',[AuthController::class,'profile'])->name('profile');
  Route::put('/perfil',[AuthController::class,'update'])->name('profile.update');
  Route::get('/catalogo',[BookController::class,'index'])->name('books.index');
  Route::post('/catalogo',[BookController::class,'store'])->middleware('role:Bibliotecario')->name('books.store');
  Route::put('/catalogo/{book}',[BookController::class,'update'])->middleware('role:Bibliotecario')->name('books.update');
  Route::delete('/catalogo/{book}',[BookController::class,'destroy'])->middleware('role:Bibliotecario')->name('books.destroy');
  Route::patch('/catalogo/{book}/restaurar',[BookController::class,'restore'])->middleware('role:Bibliotecario')->name('books.restore');
  Route::get('/mis-prestamos',[LoanController::class,'mine'])->name('loans.mine');
  Route::post('/catalogo/{book}/solicitar',[LoanController::class,'request'])->middleware('role:Lector')->name('loans.request');
  Route::patch('/prestamos/{loan}/cancelar',[LoanController::class,'cancel'])->middleware('role:Lector')->name('loans.cancel');
  Route::patch('/prestamos/{loan}/devolucion',[LoanController::class,'returnRequest'])->middleware('role:Lector')->name('loans.return.request');
  Route::get('/circulacion',[LoanController::class,'circulation'])->middleware('role:Administrador,Bibliotecario')->name('loans.circulation');
  Route::patch('/circulacion/{loan}/aprobar',[LoanController::class,'approve'])->middleware('role:Bibliotecario')->name('loans.approve');
  Route::patch('/circulacion/{loan}/rechazar',[LoanController::class,'reject'])->middleware('role:Bibliotecario')->name('loans.reject');
  Route::patch('/circulacion/{loan}/entregar',[LoanController::class,'complete'])->middleware('role:Bibliotecario')->name('loans.complete');
  Route::get('/usuarios',[UserController::class,'index'])->middleware('role:Administrador')->name('users.index');
  Route::post('/usuarios',[UserController::class,'store'])->middleware('role:Administrador')->name('users.store');
  Route::put('/usuarios/{user}',[UserController::class,'update'])->middleware('role:Administrador')->name('users.update');
  Route::delete('/usuarios/{user}',[UserController::class,'destroy'])->middleware('role:Administrador')->name('users.destroy');
  Route::patch('/usuarios/{user}/restaurar',[UserController::class,'restore'])->middleware('role:Administrador')->name('users.restore');
});
