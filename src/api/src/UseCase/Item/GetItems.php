<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Enum\Filter\SortOrder;
use App\Domain\Enum\Filter\UsersSortBy;
use App\Domain\Enum\Role;
use App\Domain\Model\Item;
use TheCodingMachine\GraphQLite\Annotations\Logged;
use TheCodingMachine\GraphQLite\Annotations\Query;
use TheCodingMachine\GraphQLite\Annotations\Security;
use TheCodingMachine\TDBM\ResultIterator;

final class GetItems
{
    private ItemDao $itemDao;

    public function __construct(ItemDao $itemDao)
    {
        $this->itemDao = $itemDao;
    }

    /**
     * @return Item[]|ResultIterator
     *
     * @Query
     */
    public function items(
        ?string $search = null
    ): ResultIterator {
        return $this->itemDao->findAll();
    }
}
