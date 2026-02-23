<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('short_link_clicks', function (Blueprint $table) {
            $table->id();

            $table->foreignId('short_link_id')
                ->constrained('short_links')
                ->cascadeOnDelete();

            // İsteğe bağlı: giriş yapan kullanıcıyı da tutmak istersen
            $table->foreignId('user_id')
                ->nullable()
                ->constrained()
                ->nullOnDelete();

            // Bu tıklama ne ile sonuçlandı?
            // redirect | disabled | not_started | expired
            $table->string('result', 32)
                ->default('redirect')
                ->index();

            // İstemci bilgileri
            $table->string('ip', 45)->nullable();          // IPv4/IPv6
            $table->text('user_agent')->nullable();        // tarayıcı/cihaz bilgisi
            $table->string('referer', 2048)->nullable();   // nereden geldi

            // Basit cihaz ayrımı (UA parse ederek doldurulur)
            $table->string('device_type', 32)->nullable(); // mobile/desktop/tablet/bot
            $table->string('browser', 64)->nullable();     // Chrome/Safari...
            $table->string('os', 64)->nullable();          // iOS/Android/Windows...

            $table->timestamp('clicked_at')->useCurrent();

            $table->index(['short_link_id', 'clicked_at']);
            $table->index(['short_link_id', 'result', 'clicked_at']);
            $table->index(['ip']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('short_link_clicks');
    }
};