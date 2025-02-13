<?php

namespace App\Service;

use App\Helper\ValidatorTrait;

class UserService
{
  use ValidatorTrait;

  /**
   * Rules for each controller method
   * 
   * @var array
   */
  public $rules = [
    'singIn' => [
      'email' => 'required|email',
      'password' => 'required|min:6'
    ]
  ];
}
