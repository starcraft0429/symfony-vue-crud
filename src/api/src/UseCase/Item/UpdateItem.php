<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Item;
use App\Domain\Throwable\InvalidModel;
use TheCodingMachine\GraphQLite\Annotations\Mutation;

final class UpdateItem
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
    public function updateItem(
        Item $item,
        string $label
    ): Item {
        return $this->update(
            $item,
            $label
        );
    }

    /**
     * @throws InvalidModel
     */
    public function update(
        Item $item,
        string $label
    ): Item {
        $item->setLabel($label);

        $this->itemDao->validate($item);
        $this->itemDao->save($item);

        return $item;
    }
}
