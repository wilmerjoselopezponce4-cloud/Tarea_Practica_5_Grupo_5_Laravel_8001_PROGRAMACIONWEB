@extends('layouts.app')
@section('title','Usuarios y roles')
@section('eyebrow','ADMINISTRACIÓN DE ACCESOS')
@section('subtitle','Gestiona las cuentas y sus permisos.')
@section('content')
<details class="panel form-disclosure"><summary>＋ Registrar usuario</summary><form method="post" action="{{ route('users.store') }}" class="form-grid">@csrf
<label>Nombre<input class="form-control custom-input" name="username" required></label><label>Correo<input type="email" class="form-control custom-input" name="email" required></label>
<label>Rol<select name="role_id" class="form-control custom-input"><option value="1">Administrador</option><option value="2">Bibliotecario</option><option value="3">Lector</option></select></label>
<label>Contraseña<input type="password" class="form-control custom-input" name="password" minlength="10" required></label><button class="primary-btn">Crear usuario</button></form></details>
<div class="panel table-panel"><div class="panel-caption"><div><span class="section-kicker">DIRECTORIO</span><h3>Miembros registrados</h3></div><span class="meta-counter">{{ $users->count() }} usuarios</span></div>
<div class="user-card-grid">@foreach($users as $u)<article class="user-card"><div class="user-card-top"><span class="avatar avatar-side">{{ mb_substr($u->username,0,1) }}</span><div><strong>{{ $u->username }}</strong><small>{{ $u->email }}</small></div></div>
<div class="user-card-bottom"><span class="status-pill">{{ $u->roleName() }}</span><span class="status-pill {{ $u->is_active?'':'muted' }}">{{ $u->is_active?'Activo':'Inactivo' }}</span></div>
<details class="edit-book"><summary>Editar usuario</summary><form method="post" action="{{ route('users.update',$u) }}" class="mini-form">@csrf @method('PUT')
<input class="form-control custom-input" name="username" value="{{ $u->username }}" required>
<input type="email" class="form-control custom-input" name="email" value="{{ $u->email }}" required>
<select class="form-control custom-input" name="role_id">@foreach([1=>'Administrador',2=>'Bibliotecario',3=>'Lector'] as $id=>$role)<option value="{{ $id }}" @selected((int)$u->role_id===(int)$id)>{{ $role }}</option>@endforeach</select>
<input type="password" class="form-control custom-input" name="password" minlength="10" placeholder="Nueva contraseña (opcional)"><button class="primary-btn">Guardar</button></form></details>
@if(auth()->id()!==$u->id)
@if($u->is_active)<form method="post" action="{{ route('users.destroy',$u) }}" data-confirm="¿Retirar acceso a esta cuenta?">@csrf @method('DELETE')<button class="text-danger-btn">Eliminar o desactivar</button></form>
@else<form method="post" action="{{ route('users.restore',$u) }}">@csrf @method('PATCH')<button class="secondary-btn">Restaurar acceso</button></form>@endif
@endif</article>@endforeach</div></div>
@endsection
