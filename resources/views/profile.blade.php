@extends('layouts.app')
@section('title','Mi perfil')
@section('eyebrow','CONFIGURACIÓN DE CUENTA')
@section('subtitle','Actualiza tus datos y protege tu acceso.')
@section('content')
<div class="profile-grid"><div class="panel profile-aside"><div class="profile-cover"><span class="profile-avatar">{{ mb_substr(auth()->user()->username,0,1) }}</span></div><div class="profile-aside-body"><h2>{{ auth()->user()->username }}</h2><p>{{ auth()->user()->roleName() }}</p><p>{{ auth()->user()->email }}</p></div></div>
<section class="panel profile-section"><h3>Datos personales y seguridad</h3><form method="post" action="{{ route('profile.update') }}" class="profile-form">@csrf @method('PUT')
<label>Nombre completo<input class="form-control custom-input" name="username" value="{{ auth()->user()->username }}" required></label>
<label>Correo<input type="email" class="form-control custom-input" name="email" value="{{ auth()->user()->email }}" required></label>
<hr><p>Solo completa los campos de contraseña si deseas cambiarla.</p>
<label>Contraseña actual<input type="password" class="form-control custom-input" name="current_password"></label>
<label>Nueva contraseña<input type="password" class="form-control custom-input" name="new_password" minlength="10"></label>
<button class="primary-btn">Guardar cambios</button></form></section></div>
@endsection
