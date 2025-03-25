<?php

namespace App\Controller;

use App\DTO\Interfaces\RequestDtoInterface;
use App\Utilities\ValidatorService;
use Symfony\Component\HttpFoundation\Exception\BadRequestException;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Serializer\SerializerInterface;

class BaseController
{
    public function __construct(protected SerializerInterface $serializer, protected ValidatorService $validator)
    {
    }

    protected function prepareRequestDto(Request $request, RequestDtoInterface $dto): RequestDtoInterface
    {
        return $this->serializer->deserialize($request->getContent(), $dto, 'json');
    }

    private function prepareAndValidateRequest(Request $request, RequestDtoInterface $dto): RequestDtoInterface
    {
        $requestDto = $this->prepareRequestDto($request, $dto);
        $errors = $this->validator->validateRequest($requestDto);

        if (empty($errors)) {
            return $requestDto;
        }

        throw new BadRequestException();
    }
}
