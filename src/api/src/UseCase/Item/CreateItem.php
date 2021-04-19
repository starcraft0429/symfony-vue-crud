<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Item;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class CreateItem
{
    private ItemDao $itemDao;

    public function __construct(
        ItemDao $itemDao
    ) {
        $this->itemDao = $itemDao;
    }

    /**
     * @throws InvalidModel
     *
     * @Mutation
     */
    public function createItem(
        string $label
    ): Item {
        return $this->create($label);
    }

    /**
     * @throws InvalidModel
     */
    public function create(
        string $label
    ): Item {
        $item = new Item($label);

        $this->itemDao->validate($item);
        $this->itemDao->save($item);

        return $item;
    }
}
