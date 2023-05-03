<?php

namespace App\Actions\Auth;

use Lorisleiva\Actions\Concerns\AsAction;

class LoginAction
{
    use AsAction;

    public function __invoke()
    {
        // Lakukan proses login di sini

        // Jika berhasil, redirect ke halaman dashboard
        return redirect('/dashboard');
    }
}
