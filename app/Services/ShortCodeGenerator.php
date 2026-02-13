<?php

namespace App\Services;

use App\Models\ShortLink;
use Illuminate\Support\Str;

class ShortCodeGenerator
{
    public function generate(int $length = 7): string
    {
        for ($i = 0; $i < 10; $i++) {
            $code = Str::lower(Str::random($length));

            if (!ShortLink::where('code', $code)->exists()) {
                return $code;
            }
        }

        // çok nadir çakışma: uzunluğu artır
        return $this->generate($length + 1);
    }
}
