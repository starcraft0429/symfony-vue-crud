<?php

declare(strict_types=1);

namespace App\UseCase\Item;

use App\Domain\Dao\ItemDao;
use App\Domain\Model\Item;
use App\Domain\Model\Category;
use App\Domain\Throwable\InvalidModel;
use App\Domain\Throwable\InvalidStorable;
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
     * @throws InvalidStorable
     *
     * @Mutation
     */
    public function createItem(
        string $label,
        Category $category
    ): Item {
        return $this->create($label, $category);
    }

    /**
     * @throws InvalidModel
     * @throws InvalidStorable
     */
    public function create(
        string $label,
        Category $category
    ): Item {
        $item = new Item($category, $label);

        $this->itemDao->validate($item);
        $this->itemDao->save($item);

        return $item;
    }
}
