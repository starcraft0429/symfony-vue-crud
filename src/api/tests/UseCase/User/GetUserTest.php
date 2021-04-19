<?php

declare(strict_types=1);

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\UseCase\User\GetUser;

use function PHPUnit\Framework\assertEquals;

it(
    'gets a user',
    function (): void {
        $userDao = self::$container->get(UserDao::class);
        assert($userDao instanceof UserDao);
        $getUser = self::$container->get(GetUser::class);
        assert($getUser instanceof GetUser);

        $user = new User(
            'foo',
            'bar',
            'foo.bar@foo.com',
            Locale::EN(),
            Role::ADMINISTRATOR()
        );
        $userDao->save($user);

        $foundUser = $getUser->user($user);
        assertEquals($user, $foundUser);
    }
)
    ->group('user');
