<?php

namespace Modules\Home\Tests\Feature;

use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class AboutControllerTest extends TestCase
{
    #[Test]
    public function it_displays_the_about_page(): void
    {
        $response = $this->get(route('about'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('home::about');
    }
}
