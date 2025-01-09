<?php

namespace Modules\Home\Tests\Feature;

use Illuminate\Http\Response;
use PHPUnit\Framework\Attributes\Test;
use Tests\TestCase;

class ContactControllerTest extends TestCase
{
    #[Test]
    public function it_displays_the_contact_page(): void
    {
        $response = $this->get(route('contact'));

        $response->assertStatus(Response::HTTP_OK)
            ->assertViewIs('home::contact');
    }
}
