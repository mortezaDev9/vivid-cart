<?php

namespace Modules\Home\Http\Controllers;

use Illuminate\View\View;

class ContactController
{
    public function __invoke(): View
    {
        return view('home::contact');
    }
}
