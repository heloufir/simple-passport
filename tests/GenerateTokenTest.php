<?php


use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GenerateTokenTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function it_rejects_if_no_email_is_specified()
    {

    }
}
