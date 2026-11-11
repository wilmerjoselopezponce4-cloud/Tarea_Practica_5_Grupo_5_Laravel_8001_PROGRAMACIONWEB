@extends('layouts.app')
@section('title','Resumen')
@section('eyebrow','PANORAMA DE LA BIBLIOTECA')
@section('subtitle','Consulta las novedades, ejemplares y préstamos de la biblioteca.')
@section('content')
<div class="hero"><div class="hero-inner"><div><span class="hero-kicker">LUMA · BIBLIOTECA DIGITAL</span><h2>Hola, {{ explode(' ',auth()->user()->username)[0] }} 👋</h2><p>Explora el catálogo y mantén tus movimientos al día.</p><a class="hero-link" href="{{ route('books.index') }}">Explorar libros →</a></div><div class="hero-illustration"><div class="hero-book hero-book-one"></div><div class="hero-book hero-book-two"></div><div class="hero-book hero-book-three"></div></div></div></div>
<div class="stats-grid">
<div class="stat-card"><div class="stat-top"><span class="stat-icon light-cyan">▣</span><span class="stat-index">CATÁLOGO</span></div><strong class="stat-value">{{ $books }}</strong><span class="stat-label">Libros registrados</span></div>
<div class="stat-card"><div class="stat-top"><span class="stat-icon light-blue">▥</span><span class="stat-index">EXISTENCIAS</span></div><strong class="stat-value">{{ $available }}</strong><span class="stat-label">Ejemplares disponibles</span></div>
<div class="stat-card"><div class="stat-top"><span class="stat-icon light-lime">◷</span><span class="stat-index">SOLICITUDES</span></div><strong class="stat-value">{{ $pending }}</strong><span class="stat-label">Pendientes de aprobación</span></div>
<div class="stat-card"><div class="stat-top"><span class="stat-icon light-pink">↗</span><span class="stat-index">PRÉSTAMOS</span></div><strong class="stat-value">{{ $borrowed }}</strong><span class="stat-label">En circulación</span></div>
</div>
<section class="panel table-panel"><div class="panel-caption"><div><span class="section-kicker">MOVIMIENTOS</span><h3>{{ auth()->user()->isReader()?'Mis movimientos recientes':'Actividad reciente de préstamos' }}</h3></div></div>
<div class="table-responsive"><table class="data-table"><thead><tr><th>LIBRO</th><th>LECTOR</th><th>ESTADO</th><th>FECHA</th></tr></thead><tbody>
@forelse($recent as $loan)<tr><td><strong>{{ $loan->book?->title }}</strong></td><td>{{ $loan->reader?->username }}</td><td><span class="status-pill">{{ $loan->status }}</span></td><td>{{ $loan->requested_at }}</td></tr>@empty<tr><td colspan="4" class="text-center py-4">Todavía no hay movimientos registrados.</td></tr>@endforelse
</tbody></table></div></section>
@endsection
