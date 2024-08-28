<?php

namespace App\Utilities;

use App\DTO\Interfaces\RequestDtoInterfagce;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ValidatorService
{
    public function __construct(protected ValidatorInterface $validator)
    {
    }

    public function validateRequest(RequestDtoInterface $dto): array
    {
        $errors = $this->validator->validate($dto);
        $errorMessage = [];

        if(count($errors) > 0){
            foreach ($errors as $key=>$error) {
                $errorMessage[$key]['code'] = '';
                $errorMessage[$key]['field'] = $error->getPropertyPath();
                $errorMessage[$key]['message'] = $error->getMessage();
            }
        }

        return $errorMessage;
    }
}