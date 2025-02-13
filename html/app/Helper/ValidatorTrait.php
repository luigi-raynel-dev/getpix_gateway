<?php

namespace App\Helper;

use Hyperf\Di\Annotation\Inject;
use Hyperf\Validation\Contract\ValidatorFactoryInterface;

trait ValidatorTrait
{

  #[Inject]
  protected ValidatorFactoryInterface $validatorFactory;

  public $validator;

  public function validate(string $rule, array $data)
  {
    if (!isset($this->rules[$rule])) {
      throw new \Exception("Rule: '{$rule}' not found.");
    }

    $validator = $this->validatorFactory->make($data, $this->rules[$rule]);
    $this->validator = $validator;

    return !$validator->fails();
  }
}
