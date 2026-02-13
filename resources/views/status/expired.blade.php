@extends('layouts.guest')

@section('title', 'Link Süresi Dolmuş')

@section('badge')
      <div class="badge">Süresi Doldu</div>
@endsection

@section('content')
      <div class="icon" style="border-color: rgba(246,195,67,.35);">
            <span style="font-size:22px;">⌛</span>
      </div>

      <h1>Bu linkin süresi dolmuş</h1>
      <p>Bu kısa linkin bitiş tarihi geçmiş. Yeni bir link isteyebilirsin.</p>

      <div class="actions">
            <a class="btn primary" href="{{ url('/') }}">Ana sayfa</a>
            <a class="btn" href="javascript:history.back()">Geri</a>
      </div>

      <div class="hint">Link sahibi yeni bir kısa link oluşturabilir veya süreyi uzatabilir.</div>
@endsection