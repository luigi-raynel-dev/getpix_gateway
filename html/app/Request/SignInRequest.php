<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class SignInRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'email' => 'required|email',
      'password' => 'required|string|min:6',
    ];
  }
}
