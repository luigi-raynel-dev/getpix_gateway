<?php

declare(strict_types=1);

namespace App\Model;

use App\Trait\MongoDBTrait;

class User
{
  use MongoDBTrait;

  protected string $table = 'users';

  public function __construct()
  {
    $this->connect();
  }
}
