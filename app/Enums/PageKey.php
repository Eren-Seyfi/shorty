<?php

namespace App\Enums;

enum PageKey: string
{
      case Home = 'home';

      case LinkNotFound = 'link_not_found';
      case LinkInactive = 'link_inactive';
      case LinkExpired = 'link_expired';

      // İstersen ek:
      // case LinkNotStarted = 'link_not_started';
      // case Maintenance = 'maintenance';

      public function label(): string
      {
            return match ($this) {
                  self::Home => 'Ana Sayfa',
                  self::LinkNotFound => 'Kırık Link',
                  self::LinkInactive => 'Pasif Link',
                  self::LinkExpired => 'Süresi Dolmuş Link',
            // self::LinkNotStarted => 'Henüz Yayında Değil',
            // self::Maintenance => 'Bakım Sayfası',
            };
      }

      /** Filament Select için */
      public static function options(): array
      {
            $out = [];

            foreach (self::cases() as $case) {
                  $out[$case->value] = $case->label();
            }

            return $out;
      }
}