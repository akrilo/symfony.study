<?php

declare(strict_types=1);

namespace App\Application\UseCase\Product;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\RequestDTO\ProductListRequestDTO;
use App\Domain\DTO\ResponseDTO\ProductListResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\Product\ProductRepositoryInterface;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class GetProductListUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository,
        private ValidatorInterface $validator
    ) { }

    public function execute(ProductListRequestDTO $requestDTO): ProductListResponseDTO|ErrorDTO
    {
        try{
            $errors = $this->validator->validate($requestDTO);

            if (count($errors) > 0) {
                throw new ValidationException;
            }

            $responseDTO = $this->productRepository->getList($requestDTO);

            if ($responseDTO === null) {
                throw new NotFoundException;
            }

            return $responseDTO;
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}