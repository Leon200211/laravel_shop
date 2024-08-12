<?php

namespace Auth\DTOs;

use App\Http\Requests\SignUpFormRequest;
use Domain\Auth\DTOs\NewUserDto;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class NewUserDTOTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     *
     * @return void
     */
    public function it_instance_created_from_form_request(): void
    {
        $dto = NewUserDto::fromRequest(new SignUpFormRequest([
            'name' => 'test',
            'email' => 'test@yandex.ru',
            'password' => 'root'
        ]));

        $this->assertInstanceOf(NewUserDto::class, $dto);
    }
}
