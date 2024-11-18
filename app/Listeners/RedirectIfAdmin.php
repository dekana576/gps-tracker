<?php

// app/Listeners/RedirectIfAdmin.php
namespace App\Listeners;

use Illuminate\Auth\Events\Login;
use Illuminate\Support\Facades\Auth;

class RedirectIfAdmin
{
    /**
     * Handle the event.
     */
    public function handle(Login $event)
    {
        $user = $event->user;

        // Cek apakah pengguna adalah admin
        if ($user->is_admin) {
            session()->put('redirect_after_login', route('admin'));
        } else {
            session()->put('redirect_after_login', route('user'));
        }
    }
}
