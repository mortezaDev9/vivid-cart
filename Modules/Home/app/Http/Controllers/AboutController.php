<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\View\View;

class AboutController
{
    public function __invoke(): View
    {
        return view('home::about');
    }
}
