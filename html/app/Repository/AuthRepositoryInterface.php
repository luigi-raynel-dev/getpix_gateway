<?php

namespace App\Repository;

use App\Request\{SignInRequest, SignUpRequest};

interface AuthRepositoryInterface
{
  public function signIn(SignInRequest $request);
  public function signUp(SignUpRequest $request);
}
