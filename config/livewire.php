<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Component Konumları
    |--------------------------------------------------------------------------
    |
    | Bu değer; tek dosyalı (SFC) veya çok dosyalı (MFC) view tabanlı
    | Livewire component’lerinin çözümleneceği kök dizinleri belirtir.
    | `make` komutu yeni component oluştururken bu dizinlerin ilkini kullanır.
    |
    */

    'component_locations' => [
        resource_path('views/components'),
        resource_path('views/livewire'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Component Namespace’leri
    |--------------------------------------------------------------------------
    |
    | Tek dosyalı veya çok dosyalı view tabanlı component’lerin çözümlenmesinde
    | kullanılacak varsayılan namespace tanımlarıdır.
    | `make` komutu ile oluşturulan component’ler de bu klasörleri referans alır.
    |
    */

    'component_namespaces' => [
        'layouts' => resource_path('views/layouts'),
        'pages' => resource_path('views/pages'),
    ],

    /*
    |--------------------------------------------------------------------------
    | Sayfa Layout’u
    |--------------------------------------------------------------------------
    |
    | Bir Livewire component’i tam sayfa olarak render edildiğinde
    | (örn: Route::livewire('/post/create', 'pages::create-post')),
    | kullanılacak ana layout view’ıdır.
    | Component içeriği bu layout içindeki $slot alanına yerleştirilir.
    |
    */

    'component_layout' => 'layouts::app',

    /*
    |--------------------------------------------------------------------------
    | Lazy Loading Placeholder
    |--------------------------------------------------------------------------
    |
    | Livewire, ilk sayfa yüklemesini hızlandırmak için component’leri
    | lazy (ertelemeli) olarak yükleyebilir.
    | Buradan tüm component’ler için varsayılan placeholder view tanımlanabilir.
    |
    */

    'component_placeholder' => null, // Örnek: 'placeholders::skeleton'

    /*
    |--------------------------------------------------------------------------
    | Make Komutu Ayarları
    |--------------------------------------------------------------------------
    |
    | `artisan make:livewire` komutunun varsayılan davranışını belirler.
    | Component tipi (sfc, mfc, class) ve isimlerde ⚡ emoji kullanımı ayarlanır.
    |
    */

    'make_command' => [
        'type' => 'sfc',  // Seçenekler: 'sfc', 'mfc', 'class'
        'emoji' => true,   // true = ⚡ prefix ekler, false = eklemez
        'with' => [
            'js' => false,
            'css' => false,
            'test' => false,
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Class Namespace
    |--------------------------------------------------------------------------
    |
    | Livewire component class’ları için kök namespace tanımıdır.
    | Otomatik component keşfi ve dosya oluşturma komutları bunu referans alır.
    |
    */

    'class_namespace' => 'App\\Livewire',

    /*
    |--------------------------------------------------------------------------
    | Class Dosya Yolu
    |--------------------------------------------------------------------------
    |
    | `artisan make:livewire` komutu çalıştırıldığında
    | component class dosyalarının oluşturulacağı dizindir.
    |
    */

    'class_path' => app_path('Livewire'),

    /*
    |--------------------------------------------------------------------------
    | View Dosya Yolu
    |--------------------------------------------------------------------------
    |
    | Livewire component Blade dosyalarının saklandığı dizindir.
    | render() metodu olmayan component’lerde otomatik kullanılır.
    |
    */

    'view_path' => resource_path('views/livewire'),

    /*
    |--------------------------------------------------------------------------
    | Geçici Dosya Yüklemeleri
    |--------------------------------------------------------------------------
    |
    | Livewire, dosya yüklemelerinde önce geçici bir dizin kullanır.
    | Dosya kalıcı olarak kaydedilmeden önce bu endpoint üzerinden yönetilir.
    |
    */

    'temporary_file_upload' => [
        'disk' => env('LIVEWIRE_TEMPORARY_FILE_UPLOAD_DISK'), // Örn: local, s3 | Varsayılan: default
        'rules' => null,                                      // Varsayılan: ['required', 'file', 'max:12288'] (12MB)
        'directory' => null,                                  // Varsayılan: livewire-tmp
        'middleware' => null,                                 // Varsayılan: throttle:60,1
        'preview_mimes' => [
            'png',
            'gif',
            'bmp',
            'svg',
            'wav',
            'mp4',
            'mov',
            'avi',
            'wmv',
            'mp3',
            'm4a',
            'jpg',
            'jpeg',
            'mpga',
            'webp',
            'wma',
        ],
        'max_upload_time' => 5, // Dakika cinsinden maksimum geçerlilik süresi
        'cleanup' => true,      // 24 saatten eski geçici dosyalar temizlensin mi?
    ],

    /*
    |--------------------------------------------------------------------------
    | Redirect Sonrası Render
    |--------------------------------------------------------------------------
    |
    | redirect(...) çağrısından sonra component’in tekrar render edilip
    | edilmeyeceğini belirler.
    |
    */

    'render_on_redirect' => false,

    /*
    |--------------------------------------------------------------------------
    | Eloquent Model Binding (Eski Davranış)
    |--------------------------------------------------------------------------
    |
    | wire:model ile doğrudan Eloquent property binding özelliği
    | önceki sürümlerde varsayılan olarak açıktı.
    | Artık "fazla sihirli" bulunduğu için feature flag altındadır.
    |
    */

    'legacy_model_binding' => false,

    /*
    |--------------------------------------------------------------------------
    | Frontend Asset’lerini Otomatik Ekleme
    |--------------------------------------------------------------------------
    |
    | Livewire JS ve CSS dosyalarını otomatik olarak <head> ve <body>
    | içine ekler. Kapatılırsa @livewireStyles ve @livewireScripts
    | manuel eklenmelidir.
    |
    */

    'inject_assets' => true,

    /*
    |--------------------------------------------------------------------------
    | Navigate (SPA Modu)
    |--------------------------------------------------------------------------
    |
    | wire:navigate kullanıldığında linkler AJAX ile yüklenir
    | ve SPA benzeri bir deneyim sunulur.
    |
    */

    'navigate' => [
        'show_progress_bar' => true,
        'progress_bar_color' => '#2299dd',
    ],

    /*
    |--------------------------------------------------------------------------
    | HTML Morph Marker’ları
    |--------------------------------------------------------------------------
    |
    | Livewire, DOM’u güncellerken Blade çıktısını akıllı şekilde
    | “morph” eder. @if, @foreach gibi yapılar için işaretleyiciler ekler.
    |
    */

    'inject_morph_markers' => true,

    /*
    |--------------------------------------------------------------------------
    | Akıllı Wire Key’ler
    |--------------------------------------------------------------------------
    |
    | Döngü içindeki nested component’ler için otomatik ve stabil key üretir.
    |
    */

    'smart_wire_keys' => true,

    /*
    |--------------------------------------------------------------------------
    | Sayfalama Teması
    |--------------------------------------------------------------------------
    |
    | WithPagination trait’i kullanıldığında render edilecek tema.
    | Tailwind varsayılandır, Bootstrap için "bootstrap" yazılabilir.
    |
    */

    'pagination_theme' => 'tailwind',

    /*
    |--------------------------------------------------------------------------
    | Release Token
    |--------------------------------------------------------------------------
    |
    | Client tarafında saklanır ve her istekte gönderilir.
    | Yeni bir release eski session’ı geçersiz kıldığında hata fırlatır.
    |
    */

    'release_token' => 'a',

    /*
    |--------------------------------------------------------------------------
    | CSP Güvenli Mod
    |--------------------------------------------------------------------------
    |
    | Sıkı Content Security Policy (CSP) kullanan uygulamalar için
    | Alpine’in CSP-safe sürümünün kullanılıp kullanılmayacağını belirler.
    |
    */

    'csp_safe' => false,

    /*
    |--------------------------------------------------------------------------
    | Payload (İstek Yükü) Güvenlik Sınırları
    |--------------------------------------------------------------------------
    |
    | Bu ayarlar; kötü niyetli veya aşırı büyük isteklerin sisteme zarar vermesini
    | (DoS vb.) engellemek için kullanılır.
    |
    */

    'payload' => [
        // RichEditor / Tiptap içeriği (JSON) bazen büyüyebilir
        'max_size' => 10 * 1024 * 1024,  // 10 MB – Maksimum istek boyutu

        // RichEditor state'i derin nested olabildiği için
        'max_nesting_depth' => 100,

        // Tek istekte çağrılabilecek maksimum method sayısı
        'max_calls' => 100,

        // Tek istekte işlenebilecek maksimum Livewire component sayısı
        'max_components' => 50,
    ],
];