@extends('layouts.app')
@section('title','Mis préstamos')
@section('eyebrow','SEGUIMIENTO PERSONAL')
@section('subtitle','Consulta las solicitudes, préstamos y devoluciones vinculados a tu cuenta.')
@section('content')
<div class="page-tabs"><a class="page-tab {{ !$history?'selected':'' }}" href="{{ route('loans.mine') }}">Activos <span>{{ $active }}</span></a><a class="page-tab {{ $history?'selected':'' }}" href="{{ route('loans.mine',['tab'=>'history']) }}">Historial <span>{{ $done }}</span></a></div>
<div class="panel table-panel"><div class="table-responsive"><table class="data-table"><thead><tr><th>LIBRO</th><th>SOLICITUD</th><th>VENCIMIENTO</th><th>ESTADO</th><th>ACCIÓN</th></tr></thead><tbody>
@forelse($loans as $loan)<tr><td><div class="book-table-name"><span class="book-thumb tone-{{ $loan->book?->cover_tone ?? 1 }}">▣</span><span><strong>{{ $loan->book?->title }}</strong><small>{{ $loan->book?->author }}</small></span></div></td>
<td>{{ $loan->requested_at }}</td><td>{{ $loan->due_date ?: 'Sin fecha' }}</td><td><span class="status-pill">{{ $loan->status }}</span></td><td>
@if(auth()->user()->isReader() && $loan->status==='Solicitado')<form method="post" action="{{ route('loans.cancel',$loan) }}">@csrf @method('PATCH')<button class="secondary-btn">Cancelar</button></form>
@elseif(auth()->user()->isReader() && $loan->status==='Prestado')<form method="post" action="{{ route('loans.return.request',$loan) }}">@csrf @method('PATCH')<button class="secondary-btn">Solicitar devolución</button></form>
@else<span class="muted-small">Sin acciones</span>@endif
</td></tr>
@empty<tr><td colspan="5"><div class="table-empty">No hay préstamos en esta categoría.</div></td></tr>@endforelse
</tbody></table></div></div><div class="info-banner">Las solicitudes se confirman al ser aprobadas por un bibliotecario. Las devoluciones se cierran cuando el ejemplar se recibe.</div>
@endsection
