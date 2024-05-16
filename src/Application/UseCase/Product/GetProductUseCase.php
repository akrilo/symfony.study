<?php

declare(strict_types=1);

namespace App\Application\UseCase\Product;

use App\Application\DTO\ErrorDTO;
use App\Domain\DTO\ResponseDTO\ProductResponseDTO;
use App\Domain\Exception\NotFoundException;
use App\Domain\Exception\ValidationException;
use App\Domain\Product\ProductRepositoryInterface;
use Symfony\Component\Uid\Uuid;

class GetProductUseCase
{
    public function __construct(
        private ProductRepositoryInterface $productRepository
    ) { }

    public function execute(string $uuid): ProductResponseDTO|ErrorDTO
    {
        try{
            if (!Uuid::isValid($uuid)) {
                throw new ValidationException;
            }

            $responseDTO = $this->productRepository->findById($uuid);

            if ($responseDTO === null) {
                throw new NotFoundException;
            }

            return $responseDTO;
        } catch (ValidationException | NotFoundException $e) {
            return ErrorDTO::toDTO($e);
        }
    }
}