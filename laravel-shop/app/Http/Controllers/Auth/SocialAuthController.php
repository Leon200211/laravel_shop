<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Domain\Auth\Models\User;
use DomainException;
use Illuminate\Http\RedirectResponse;
use Laravel\Socialite\Facades\Socialite;
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
            "{$driver}_id" => $userSocialite->id,
        ], [
            'name' => $userSocialite->nickname,
            'email' => $userSocialite->email,
            'password' => bcrypt(str()->random(20))
        ]);

        auth()->login($user);

        return redirect()->intended(route('home'));
    }
}
