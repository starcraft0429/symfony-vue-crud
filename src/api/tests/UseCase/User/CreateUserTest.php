<?php

declare(strict_types=1);

use App\Domain\Enum\Locale;
use App\Domain\Enum\Role;
use App\Domain\Model\Storable\ProfilePicture;
use App\Domain\Throwable\InvalidModel;
use App\Tests\UseCase\DummyValues;
use App\UseCase\User\CreateUser;

use function PHPUnit\Framework\assertEquals;
use function PHPUnit\Framework\assertNotNull;
use function PHPUnit\Framework\assertNull;

it(
    'creates a user',
    function (
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role,
        ?string $filename
    ): void {
        $createUser = self::$container->get(CreateUser::class);
        assert($createUser instanceof CreateUser);

        $storable = null;
        if ($filename !== null) {
            $storable = ProfilePicture::createFromPath(
                dirname(__FILE__) . '/' . $filename
            );
        }

        $user = $createUser->create(
            $firstName,
            $lastName,
            $email,
            $locale,
            $role,
            $storable
        );

        assertEquals($firstName, $user->getFirstName());
        assertEquals($lastName, $user->getLastName());
        assertEquals($email, $user->getEmail());
        assertNull($user->getPassword());
        assertEquals($locale, $user->getLocale());
        assertEquals($role, $user->getRole());

        if ($filename !== null) {
            assertNotNull($user->getProfilePicture());
        } else {
            assertNull($user->getProfilePicture());
        }
    }
)
    ->with([
        ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::ADMINISTRATOR(), null],
        ['foo', 'bar', 'foo.bar@baz.com', Locale::EN(), Role::USER(), 'foo.png'],
    ])
    ->group('user');

it(
    'throws an exception if invalid user',
    function (
        string $firstName,
        string $lastName,
        string $email,
        Locale $locale,
        Role $role
    ): void {
        $createUser = self::$container->get(CreateUser::class);
        assert($createUser instanceof CreateUser);

        // We create a user for checking if an
        // email is not unique.
        $createUser->createUser(
            'foo',
            'bar',
            'foo@bar.com',
            Locale::EN(),
            Role::USER()
        );

        $createUser->createUser(
            $firstName,
            $lastName,
            $email,
            $locale,
            $role
        );
    }
)
    ->with([
        // Blank first name.
        [DummyValues::BLANK, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // First name > 255.
        [DummyValues::CHAR256, 'bar', 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Blank last name.
        ['foo', DummyValues::BLANK, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Last name > 255.
        ['foo', DummyValues::CHAR256, 'foo@foo.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Existing e-mail.
        ['foo', 'far', 'foo@bar.com', Locale::EN(), Role::ADMINISTRATOR()],
        // Invalid e-mail.
        ['foo', 'far', 'foo', Locale::EN(), Role::ADMINISTRATOR()],
    ])
    ->throws(InvalidModel::class)
    ->group('user');
