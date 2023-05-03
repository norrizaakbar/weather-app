<?php

namespace App\Actions\Auth;

use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Lorisleiva\Actions\Concerns\AsAction;

class LogoutAction
{
    use AsAction;

    public function __construct()
    {
        $this->middleware('auth');
    }

    public function handle()
    {
        Auth::logout();

        return Redirect::route('/login');
    }
}
