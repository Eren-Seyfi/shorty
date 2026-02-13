@extends('layouts.guest')

@section('title', 'Link Henüz Aktif Değil')

@section('badge')
      <div class="badge">Planlı</div>
@endsection

@section('content')
      <div class="icon" style="border-color: rgba(246,195,67,.35);">
            <span style="font-size:22px;">🕒</span>
      </div>

      <h1>Link henüz aktif değil</h1>
      <p>Bu kısa link için başlangıç tarihi ayarlanmış. Lütfen daha sonra tekrar dene.</p>

      <div class="actions">
            <a class="btn primary" href="{{ url('/') }}">Ana sayfa</a>
            <a class="btn" href="javascript:history.back()">Geri</a>
      </div>

      <div class="hint">Planlı bir yayın olduğu için link şu an yönlendirme yapmıyor.</div>
@endsection