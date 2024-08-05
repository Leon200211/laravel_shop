<?php

namespace Domain\Auth\Contracts;

use Domain\Auth\DTOs\NewUserDto;

interface RegisterNewUserContract
{
    public function __invoke(NewUserDto $data);
}
