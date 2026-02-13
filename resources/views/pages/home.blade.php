@extends('layouts.guest')

@section('title', config('app.name'))

@section('badge')
      <div class="badge">Akıllı Link Kısaltma ve Yönetim</div>
@endsection


@section('content')
      <div class="icon">
            <span style="font-size:22px;">🔗</span>
      </div>

      <h1>{{ config('app.name') }}</h1>

      <p>
            Uzun bağlantılarını kısa, temiz ve paylaşılabilir linklere dönüştür.
            Linklerini dilediğin zaman açıp kapatabilir, başlangıç ve bitiş tarihleriyle kontrol edebilirsin.
      </p>


      <div class="badge">Link Kısaltma Platformu</div>

@endsection