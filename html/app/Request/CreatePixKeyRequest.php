<?php

declare(strict_types=1);

namespace App\Request;

use Hyperf\Validation\Request\FormRequest;

class CreatePixKeyRequest extends FormRequest
{
  public function authorize()
  {
    return true;
  }

  public function rules(): array
  {
    return [
      'key' => 'required|string',
      'type' => 'required|string|in:cpf,cnpj,email,phone,random',
      'bankISPB' => 'required|string|min:8|max:10',
      'belongsTo' => 'string',
    ];
  }
}
