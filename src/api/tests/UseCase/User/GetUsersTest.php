<?php

declare(strict_types=1);

use App\Domain\Dao\UserDao;
use App\Domain\Enum\Filter\SortOrder;
use App\Domain\Enum\Filter\UsersSortBy;
use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\User;
use App\UseCase\User\GetUsers;

use function PHPUnit\Framework\assertCount;
use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertStringContainsStringIgnoringCase;

beforeEach(function (): void {
    $userDao = self::$container->get(UserDao::class);
    assert($userDao instanceof UserDao);

    $user = new User(
        'a',
        'a',
        'a.a@a.a',
        Locale::EN(),
        Role::ADMINISTRATOR()
    );
    $userDao->save($user);

    $user = new User(
        'b',
        'b',
        'b.b@b.b',
        Locale::EN(),
        Role::USER()
    );
    $userDao->save($user);

    $user = new User(
        'c',
        'c',
        'c.c@c.c',
        Locale::EN(),
        Role::USER()
    );
    $userDao->save($user);
});

it(
    'finds all users',
    function (): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users();
        assertCount(3, $result);
    }
)
    ->group('user');

it(
    'filters users with a generic search',
    function (string $search): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users($search);
        assertCount(1, $result);

        $user = $result->first();
        assert($user instanceof User);
        assertStringContainsStringIgnoringCase($search, $user->getFirstName());
        assertStringContainsStringIgnoringCase($search, $user->getLastName());
        assertStringContainsStringIgnoringCase($search, $user->getEmail());
    }
)
    ->with(['a', 'b', 'c'])
    ->group('user');

it(
    'filters users by role',
    function (Role $role): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users(null, $role);

        if ($role->equals(Role::ADMINISTRATOR())) {
            assertCount(1, $result);
        }

        if ($role->equals(Role::USER())) {
            assertCount(2, $result);
        }

        $user = $result->first();
        assert($user instanceof User);
        assertEquals($role, $user->getRole());
    }
)
    ->with([Role::ADMINISTRATOR(), Role::USER()])
    ->group('user');

it(
    'sorts users by first name',
    function (SortOrder $sortOrder): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users(null, null, UsersSortBy::FIRST_NAME(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();
        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getFirstName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getFirstName());
            assertStringContainsStringIgnoringCase('c', $users[2]->getFirstName());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getFirstName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getFirstName());
            assertStringContainsStringIgnoringCase('c', $users[0]->getFirstName());
        }
    }
)
    ->with([SortOrder::ASC(), SortOrder::DESC()])
    ->group('user');

it(
    'sorts users by last name',
    function (SortOrder $sortOrder): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users(null, null, UsersSortBy::LAST_NAME(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();
        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getLastName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getLastName());
            assertStringContainsStringIgnoringCase('c', $users[2]->getLastName());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getLastName());
            assertStringContainsStringIgnoringCase('b', $users[1]->getLastName());
            assertStringContainsStringIgnoringCase('c', $users[0]->getLastName());
        }
    }
)
    ->with([SortOrder::ASC(), SortOrder::DESC()])
    ->group('user');

it(
    'sorts users by e-mail',
    function (SortOrder $sortOrder): void {
        $getUsers = self::$container->get(GetUsers::class);
        assert($getUsers instanceof GetUsers);

        $result = $getUsers->users(null, null, UsersSortBy::EMAIL(), $sortOrder);
        assertCount(3, $result);

        /** @var User[] $users */
        $users = $result->toArray();

        if ($sortOrder->equals(SortOrder::ASC())) {
            assertStringContainsStringIgnoringCase('a', $users[0]->getEmail());
            assertStringContainsStringIgnoringCase('b', $users[1]->getEmail());
            assertStringContainsStringIgnoringCase('c', $users[2]->getEmail());
        } else {
            assertStringContainsStringIgnoringCase('a', $users[2]->getEmail());
            assertStringContainsStringIgnoringCase('b', $users[1]->getEmail());
            assertStringContainsStringIgnoringCase('c', $users[0]->getEmail());
        }
    }
)
    ->with([SortOrder::ASC(), SortOrder::DESC()])
    ->group('user');
