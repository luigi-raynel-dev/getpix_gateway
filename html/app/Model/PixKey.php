<?php

declare(strict_types=1);

namespace App\Model;

use App\Trait\MongoDBConnection;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

class PixKey
{
  use MongoDBConnection;

  protected string $table = 'pixKeys';

  public ?Collection $collection;

  public function __construct()
  {
    $this->connect();
  }

  public function findById(string $id)
  {
    return $this->collection->findOne(
      ['_id' => new ObjectId($id)]
    );
  }
}
