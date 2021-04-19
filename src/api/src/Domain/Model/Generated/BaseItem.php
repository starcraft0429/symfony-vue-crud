<?php
/**
 * This file has been automatically generated by TDBM.
 *
 * DO NOT edit this file, as it might be overwritten.
 * If you need to perform changes, edit the Item class instead!
 */

declare(strict_types=1);

namespace App\Domain\Model\Generated;

use App\Domain\Model\Category;
use TheCodingMachine\TDBM\AbstractTDBMObject;
use TheCodingMachine\TDBM\ResultIterator;
use TheCodingMachine\TDBM\AlterableResultIterator;
use Ramsey\Uuid\Uuid;
use JsonSerializable;
use TheCodingMachine\TDBM\Schema\ForeignKeys;
use TheCodingMachine\GraphQLite\Annotations\Field as GraphqlField;

/**
 * The BaseItem class maps the 'items' table in database.
 */
abstract class BaseItem extends \TheCodingMachine\TDBM\AbstractTDBMObject implements JsonSerializable
{

    /**
     * @var \TheCodingMachine\TDBM\Schema\ForeignKeys
     */
    private static $foreignKeys = null;

    /**
     * The constructor takes all compulsory arguments.
     *
     * @param \App\Domain\Model\Category $category
     * @param string $label
     */
    public function __construct(\App\Domain\Model\Category $category, string $label)
    {
        parent::__construct();
        $this->setCategory($category);
        $this->setLabel($label);
    }

    /**
     * The getter for the "id" column.
     *
     * @return int|null
     */
    public function getId() : ?int
    {
        return $this->get('id', 'items');
    }

    /**
     * The setter for the "id" column.
     *
     * @param int|null $id
     */
    public function setId(?int $id) : void
    {
        $this->set('id', $id, 'items');
    }

    /**
     * Returns the Category object bound to this object via the category_id column.
     */
    public function getCategory() : \App\Domain\Model\Category
    {
        return $this->getRef('from__category_id__to__table__categories__columns__id', 'items');
    }

    /**
     * The setter for the Category object bound to this object via the category_id
     * column.
     */
    public function setCategory(\App\Domain\Model\Category $object) : void
    {
        $this->setRef('from__category_id__to__table__categories__columns__id', $object, 'items');
    }

    /**
     * The getter for the "label" column.
     *
     * @return string
     */
    public function getLabel() : string
    {
        return $this->get('label', 'items');
    }

    /**
     * The setter for the "label" column.
     *
     * @param string $label
     */
    public function setLabel(string $label) : void
    {
        $this->set('label', $label, 'items');
    }

    /**
     * Internal method used to retrieve the list of foreign keys attached to this bean.
     */
    protected static function getForeignKeys(string $tableName) : \TheCodingMachine\TDBM\Schema\ForeignKeys
    {
        if ($tableName === 'items') {
            if (self::$foreignKeys === null) {
                self::$foreignKeys = new ForeignKeys([
                    'from__category_id__to__table__categories__columns__id' => [
                        'foreignTable' => 'categories',
                        'localColumns' => [
                            'category_id'
                        ],
                        'foreignColumns' => [
                            'id'
                        ]
                    ]
                ]);
            }
            return self::$foreignKeys;
        }
        return parent::getForeignKeys($tableName);
    }

    /**
     * Serializes the object for JSON encoding.
     *
     * @param bool $stopRecursion Parameter used internally by TDBM to stop embedded
     * objects from embedding other objects.
     * @return array
     */
    public function jsonSerialize(bool $stopRecursion = false)
    {
        $array = [];
        $array['id'] = $this->getId();
        if ($stopRecursion) {
            $array['category'] = ['id' => $this->getCategory()->getId()];
        } else {
            $array['category'] = $this->getCategory()->jsonSerialize(true);
        }
        $array['label'] = $this->getLabel();
        return $array;
    }

    /**
     * Returns an array of used tables by this bean (from parent to child
     * relationship).
     *
     * @return string[]
     */
    public function getUsedTables() : array
    {
        return [ 'items' ];
    }

    /**
     * Method called when the bean is removed from database.
     */
    public function onDelete() : void
    {
        parent::onDelete();
        $this->setRef('from__category_id__to__table__categories__columns__id', null, 'items');
    }

    public function __clone()
    {
        parent::__clone();
    }
}
