<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class SignUpRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'firstName' => 'required|string|min:2',
      'lastName' => 'required|string|min:2',
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ];
  }
}
