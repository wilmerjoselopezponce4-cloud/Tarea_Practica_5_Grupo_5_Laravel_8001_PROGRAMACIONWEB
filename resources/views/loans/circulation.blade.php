@extends('layouts.app')
@section('title','Préstamos y devoluciones')
@section('eyebrow','CONTROL DE CIRCULACIÓN')
@section('subtitle','Revisión de solicitudes, préstamos activos y devoluciones.')
@section('content')
<div class="mini-stats"><div class="mini-stat"><span class="mini-icon light-cyan">◷</span><div><strong>{{ $counts['pending'] }}</strong><small>Pendientes</small></div></div><div class="mini-stat"><span class="mini-icon light-lime">▣</span><div><strong>{{ $counts['active'] }}</strong><small>En préstamo</small></div></div><div class="mini-stat"><span class="mini-icon light-blue">✓</span><div><strong>{{ $counts['history'] }}</strong><small>Finalizados</small></div></div></div>
<div class="page-tabs tabs-wrap">@foreach(['pending'=>'Solicitudes','active'=>'En préstamo','history'=>'Historial','all'=>'Todos'] as $key=>$label)<a href="{{ route('loans.circulation',['tab'=>$key]) }}" class="page-tab {{ $tab===$key?'selected':'' }}">{{ $label }} <span>{{ $counts[$key] }}</span></a>@endforeach</div>
<div class="panel table-panel"><div class="table-responsive"><table class="data-table"><thead><tr><th>LIBRO</th><th>LECTOR</th><th>FECHA</th><th>VENCIMIENTO</th><th>ESTADO</th>@if(auth()->user()->isLibrarian())<th>ACCIÓN</th>@endif</tr></thead><tbody>
@forelse($loans as $loan)<tr><td><strong>{{ $loan->book?->title }}</strong><small class="d-block">{{ $loan->book?->author }}</small></td><td>{{ $loan->reader?->username }}</td><td>{{ $loan->requested_at }}</td><td>{{ $loan->due_date ?? '-' }}</td><td><span class="status-pill">{{ $loan->status }}</span></td>
@if(auth()->user()->isLibrarian())<td><div class="table-actions">
@if($loan->status==='Solicitado')<form method="post" action="{{ route('loans.approve',$loan) }}">@csrf @method('PATCH')<button class="secondary-btn">Aprobar</button></form><form method="post" action="{{ route('loans.reject',$loan) }}">@csrf @method('PATCH')<button class="text-danger-btn">Rechazar</button></form>
@elseif(in_array($loan->status,['Prestado','Devolucion solicitada'],true))<form method="post" action="{{ route('loans.complete',$loan) }}">@csrf @method('PATCH')<button class="secondary-btn">Confirmar devolución</button></form>
@else<span class="muted-small">Finalizado</span>@endif
</div></td>@endif</tr>
@empty<tr><td colspan="6"><div class="table-empty">No hay movimientos registrados.</div></td></tr>@endforelse
</tbody></table></div></div><div class="info-banner">Los préstamos tienen 14 días de duración. Al aprobar se descuenta un ejemplar y al cerrar la devolución se repone.</div>
@endsection
