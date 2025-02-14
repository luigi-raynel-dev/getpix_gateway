<?php

declare(strict_types=1);

namespace App\Trait;

use App\Factory\MongoDBFactory;
use MongoDB\Collection;

trait MongoDBTrait
{
  protected ?Collection $collection = null;

  public function connect()
  {
    $db = MongoDBFactory::getDatabase();

    if (isset($this->table)) {
      $this->collection = $db->selectCollection($this->table);
    } else {
      throw new \Exception('$table not defined.');
    }

    return $this->collection;
  }
}
