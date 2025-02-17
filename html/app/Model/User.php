<?php

declare(strict_types=1);

namespace App\Model;

use App\Trait\MongoDBConnection;
use MongoDB\Collection;

class User
{
  use MongoDBConnection;

  protected string $table = 'users';

  public ?Collection $collection;

  public function __construct()
  {
    $this->connect();
  }
}
