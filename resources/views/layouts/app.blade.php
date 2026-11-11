<!doctype html><html lang="es"><head><meta charset="utf-8"><meta name="viewport" content="width=device-width,initial-scale=1">
<title>@yield('title','Panel') · Luma Biblioteca</title><link rel="icon" href="{{ asset('favicon.svg') }}" type="image/svg+xml">
<link rel="stylesheet" href="{{ asset('vendor/bootstrap.min.css') }}"><link rel="stylesheet" href="{{ asset('base.css') }}"><link rel="stylesheet" href="{{ asset('fresh.css') }}"></head>
<body class="app-body"><div class="shell">
<aside class="sidebar" id="sidebar"><a class="brand" href="{{ route('dashboard') }}"><span class="brand-icon">▣</span><span class="brand-words">luma<span class="brand-dot">.</span><small>BIBLIOTECA DIGITAL</small></span></a>
<div class="side-group-label">NAVEGACIÓN</div><nav class="side-nav" aria-label="Navegación principal">
@foreach([['dashboard','Resumen','▦'],['books.index','Catálogo','▣'],['loans.mine','Mis préstamos','▱']] as [$route,$label,$icon])
<a class="side-link {{ request()->routeIs($route)?'active':'' }}" href="{{ route($route) }}"><span class="nav-icon">{{ $icon }}</span><span>{{ $label }}</span></a>
@endforeach
@if(auth()->user()->isAdmin() || auth()->user()->isLibrarian())<a class="side-link {{ request()->routeIs('loans.circulation')?'active':'' }}" href="{{ route('loans.circulation') }}"><span class="nav-icon">↗</span><span>Préstamos y devoluciones</span></a>@endif
@if(auth()->user()->isAdmin())<a class="side-link {{ request()->routeIs('users.index')?'active':'' }}" href="{{ route('users.index') }}"><span class="nav-icon">◎</span><span>Usuarios y roles</span></a>@endif
<a class="side-link {{ request()->routeIs('profile')?'active':'' }}" href="{{ route('profile') }}"><span class="nav-icon">⚙</span><span>Mi perfil</span></a></nav>
<div class="sidebar-spacer"></div><div class="team-panel"><span class="team-emblem">G5</span><div><strong>Grupo 05</strong><small>Programación Web Avanzada</small></div></div>
<div class="sidebar-user"><span class="avatar avatar-side">{{ mb_substr(auth()->user()->username,0,1) }}</span><span class="sidebar-user-meta"><strong>{{ auth()->user()->username }}</strong><small>{{ auth()->user()->roleName() }}</small></span>
<form method="post" action="{{ route('logout') }}" class="side-logout">@csrf<button class="icon-btn" title="Cerrar sesión">↪</button></form></div></aside>
<div class="main-shell"><header class="topbar"><div class="topbar-left"><button class="mobile-trigger" type="button" id="sidebarTrigger" >☰</button><div class="breadcrumbs">ESPACIO DE TRABAJO <span>/</span> <strong>@yield('title','Resumen')</strong></div></div><div class="topbar-right"><span class="system-tag"><i></i> Sistema operativo</span><span class="top-avatar">{{ mb_substr(auth()->user()->username,0,1) }}</span></div></header>
<main class="content"><div class="page-heading"><div class="eyebrow"><span></span>@yield('eyebrow','GRUPO 05')</div><h1>@yield('title','Resumen')</h1><p>@yield('subtitle','')</p></div>
@if(session('ok'))<div class="flash flash-success" role="status"><span>✓</span>{{ session('ok') }}</div>@endif
@if($errors->any())<div class="flash flash-error" role="alert"><span>!</span>{{ $errors->first() }}</div>@endif
@yield('content')
</main><footer class="footer"><span>LUMA · BIBLIOTECA DIGITAL</span><span>Grupo 5 · Jorge Toapanta · Wilmer Adriano · Jhoely Dávila · Jhonatan Chávez · Wilmer López</span></footer></div></div><div class="sidebar-overlay" id="sidebarOverlay"></div>
<script src="{{ asset('vendor/bootstrap.bundle.min.js') }}"></script><script src="{{ asset('assets.js') }}"></script></body></html>
