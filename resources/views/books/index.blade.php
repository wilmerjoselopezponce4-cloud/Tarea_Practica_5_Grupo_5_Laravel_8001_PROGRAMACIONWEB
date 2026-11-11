@extends('layouts.app')
@section('title','Catálogo')
@section('eyebrow','COLECCIÓN DISPONIBLE')
@section('subtitle','Busca libros por título, autor o género.')
@section('content')
@if(auth()->user()->isLibrarian())<details class="panel form-disclosure"><summary>＋ Agregar libro al catálogo</summary>
<form class="form-grid" method="post" action="{{ route('books.store') }}">@csrf
<label class="form-wide">Título<input name="title" class="form-control custom-input" required maxlength="160"></label>
<label>Autor<input name="author" class="form-control custom-input" required maxlength="100"></label>
<label>Género<input name="genre" class="form-control custom-input" required maxlength="60"></label>
<label>Año<input type="number" min="1450" max="{{ date('Y')+1 }}" name="year" class="form-control custom-input"></label>
<label>Ejemplares<input type="number" min="0" max="9999" name="copies_total" value="1" class="form-control custom-input" required></label>
<label>ISBN<input name="isbn" class="form-control custom-input"></label>
<label>Color de portada<select name="cover_tone" class="form-control custom-input">@for($n=1;$n<=6;$n++)<option value="{{ $n }}">Colección {{ $n }}</option>@endfor</select></label>
<label class="form-wide">Sinopsis<textarea name="description" class="form-control custom-input" rows="2"></textarea></label>
<button class="primary-btn">Guardar libro</button></form></details>@endif
<div class="catalog-top"><form method="get" class="catalog-toolbar"><input type="search" name="q" value="{{ request('q') }}" class="form-control search-control" placeholder="Título o autor">
<select name="genre" class="filter-select"><option value="">Todos los géneros</option>@foreach($genres as $genre)<option value="{{ $genre }}" @selected(request('genre')===$genre)>{{ $genre }}</option>@endforeach</select>
<button class="secondary-btn" type="submit">Buscar</button><a class="reset-link" href="{{ route('books.index') }}">Limpiar</a></form></div>
<div class="catalog-grid">
@forelse($books as $book)<article class="catalog-card"><div class="catalog-cover-wrap tone-{{ $book->cover_tone }}"><div class="book-cover-visual"><span>▣</span><strong>{{ Str::limit($book->title,34) }}</strong><small>{{ $book->author }}</small></div><span class="genre-tag">{{ $book->genre }}</span></div>
<div class="catalog-details"><div class="book-year">{{ $book->year ?: 'Edición sin año' }}</div><h3>{{ $book->title }}</h3><div class="book-author">{{ $book->author }}</div><p class="book-description">{{ Str::limit($book->description,135) }}</p><div class="book-meta"><span class="stock {{ $book->quantity<1?'out':'' }}">{{ $book->quantity }} disponibles</span><span class="book-isbn">{{ $book->copies_total }} ejemplares</span></div>
<div class="catalog-actions">
@if(auth()->user()->isReader())
@if($activeIds->contains($book->id))<span class="card-action muted">Solicitud activa</span>
@elseif($book->quantity>0)<form method="post" action="{{ route('loans.request',$book) }}">@csrf<button class="card-action">Solicitar libro →</button></form>
@else<span class="card-action muted">Sin existencias</span>@endif
@elseif(auth()->user()->isLibrarian())
<details class="edit-book"><summary>Editar libro</summary><form method="post" action="{{ route('books.update',$book) }}" class="mini-form">@csrf @method('PUT')
<input name="title" class="form-control custom-input" value="{{ $book->title }}" required><input name="author" class="form-control custom-input" value="{{ $book->author }}" required><input name="genre" class="form-control custom-input" value="{{ $book->genre }}" required>
<div class="mini-fields"><input type="number" name="year" class="form-control custom-input" min="1450" max="{{ date('Y')+1 }}" value="{{ $book->year }}"><input type="number" name="copies_total" min="0" max="9999" class="form-control custom-input" value="{{ $book->copies_total }}" required></div>
<input name="isbn" class="form-control custom-input" value="{{ $book->isbn }}"><select name="cover_tone" class="form-control custom-input">@for($n=1;$n<=6;$n++)<option value="{{ $n }}" @selected((int)$book->cover_tone===(int)$n)>Portada {{ $n }}</option>@endfor</select>
<textarea name="description" class="form-control custom-input" rows="2">{{ $book->description }}</textarea><button class="primary-btn">Guardar cambios</button></form></details>
@if($book->is_active)<form method="post" action="{{ route('books.destroy',$book) }}" data-confirm="¿Retirar este libro?">@csrf @method('DELETE')<button class="text-danger-btn">Retirar</button></form>
@else<form method="post" action="{{ route('books.restore',$book) }}">@csrf @method('PATCH')<button class="card-action">Restaurar</button></form>@endif
@else<span class="catalog-view-only">Consulta del catálogo</span>@endif
</div></div></article>
@empty<div class="panel empty-state">No se encontraron libros.</div>@endforelse</div>
@endsection
