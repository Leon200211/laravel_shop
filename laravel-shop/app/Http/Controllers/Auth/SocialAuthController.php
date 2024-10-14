<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
use Support\SessionRegenerator;
use Throwable;

class SocialAuthController extends Controller
{
    public function redirect(string $driver): RedirectResponse
    {
        try {
            return Socialite::driver($driver)->redirect();
        } catch (Throwable $exception) {
            throw new DomainException('Произошла ошибка или драйвер не поддерживается!');
        }
    }

    public function callback(string $driver): RedirectResponse
    {
        $userSocialite = Socialite::driver($driver)->user();

        // TODO вынести все идентификаторы в отдельную таблицу (социал_ауф)

        $user = User::query()->updateOrCreate([
            "{$driver}_id" => $userSocialite->getId(),
        ], [
            'name' => $userSocialite->getNickname(),
            'email' => $userSocialite->getEmail(),
            'password' => bcrypt(str()->random(20))
        ]);

        SessionRegenerator::run(fn () =>  auth()->login($user));

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
