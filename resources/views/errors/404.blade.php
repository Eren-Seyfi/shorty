@extends('layouts.guest')

@section('title', 'Sayfa Bulunamadı')

@section('badge')
      <div class="badge">404</div>
@endsection

@section('content')
      <div class="icon" style="border-color: rgba(246,195,67,.35);">
            <span style="font-size:22px;">🔎</span>
      </div>

      <h1>Sayfa bulunamadı</h1>
      <p>Aradığın sayfa yok veya taşınmış olabilir.</p>

      <div class="actions">
            <a class="btn primary" href="{{ url('/') }}">Ana sayfa</a>
            <a class="btn" href="javascript:history.back()">Geri</a>
      </div>
@endsection