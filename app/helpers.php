<?php

declare(strict_types=1);

if (! function_exists('toast')) {
    function toast(string $type, string $message): void
    {
        session()->flash($type, $message);
    }
}
