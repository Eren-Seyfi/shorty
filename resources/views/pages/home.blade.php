@extends('layouts.guest')

@section('title', $title ?? config('app.name'))

@section('badge')
      @if(!empty($badge))
            <div class="badge">{{ $badge }}</div>
      @endif
@endsection

@section('content')
      <div class="fi-prose">
            {!! $html !!}
      </div>
@endsection