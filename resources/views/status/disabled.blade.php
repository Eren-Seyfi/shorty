@extends('layouts.guest')

@section('title', 'Link Pasif')

@section('badge')
      <div class="badge">Pasif</div>
@endsection

@section('content')
      <div class="icon" style="border-color: rgba(251,113,133,.35);">
            <span style="font-size:22px;">⛔</span>
      </div>

      <h1>Bu link pasif</h1>
      <p>Bu kısa link şu an kapalı. Link sahibi tekrar aktif ettiğinde çalışacaktır.</p>

      <div class="actions">
            <a class="btn primary" href="{{ url('/') }}">Ana sayfa</a>
            <a class="btn" href="javascript:history.back()">Geri</a>
      </div>

      <div class="hint">Eğer bu linki bekliyorsan, link sahibinden aktif etmesini isteyebilirsin.</div>
@endsection