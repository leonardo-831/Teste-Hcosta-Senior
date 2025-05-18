<?php

namespace App\Modules\Auth\Application\Services;

use App\Modules\Auth\Application\Events\UserLogout;
use App\Modules\Auth\Application\Events\UserLogin;
use App\Modules\Auth\Domain\Entities\User;
use App\Modules\Auth\Domain\Repositories\UserRepositoryInterface;
use Exception;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthService
{
    public function __construct(
        protected UserRepositoryInterface $repo
    ) {}

    public function register(array $data): User
    {
        $data['password'] = Hash::make($data['password']);
        return $this->repo->create($data);
    }

    public function login(array $credentials): ?string
    {
        if (!$token = JWTAuth::attempt($credentials)) {
            throw new Exception("Não foi possível autenticar, confirme suas credenciais!");
        }

        unset($credentials['password']);

        Event::dispatch(
            new UserLogin(
                $credentials,
                userName: auth()->user()->name,
                ip: request()->ip(),
                route: request()->path(),
            )
        );

        return $token;
    }

    public function logout(): void
    {
        $token = JWTAuth::getToken();
        if (!$token) {
            throw new \Exception('Token ausente');
        }

        Event::dispatch(
            new UserLogout(
                [],
                userName: auth()->user()->name,
                ip: request()->ip(),
                route: request()->path(),
            )
        );
        JWTAuth::invalidate($token);
    }
}
