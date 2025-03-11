<?php

declare(strict_types=1);

namespace App\Model;

use App\Trait\MongoDBConnection;
use MongoDB\Collection;
use MongoDB\BSON\ObjectId;

class User
{
  use MongoDBConnection;

  protected string $table = 'users';

  public ?Collection $collection;

  public function __construct()
  {
    $this->connect();
  }

  protected $projection = [
    'password' => 0
  ];

  public function findById(string $id)
  {
    return $this->collection->findOne(
      ['_id' => new ObjectId($id)],
      ['projection' => $this->projection]
    );
  }
}
