<?php

declare(strict_types=1);

namespace App\Trait;

use App\Factory\MongoDBFactory;


trait MongoDBConnection
{
  public function connect()
  {
    $db = MongoDBFactory::getDatabase();

    if (isset($this->table)) {
      $this->collection = $db->selectCollection($this->table);
    } else {
      throw new \Exception('$collection not defined.');
    }

    return $this->collection;
  }
}
