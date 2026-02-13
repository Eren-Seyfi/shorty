<?php

namespace App\Http\Controllers;

class ShortLinkStatusController extends Controller
{
    public function __invoke(string $reason)
    {
        $view = match ($reason) {
            'disabled' => 'status.disabled',
            'not-started' => 'status.not-started',
            'expired' => 'status.expired',
            default => null,
        };

        if (!$view) {
            abort(404);
        }

        return view($view);
    }
}
