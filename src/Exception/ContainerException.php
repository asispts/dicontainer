<?php declare(strict_types=1);

namespace DiContainer\Exception;

use Psr\Container\ContainerExceptionInterface;
use RuntimeException;

// phpcs:ignore SlevomatCodingStandard.Classes.RequireAbstractOrFinal
class ContainerException extends RuntimeException implements ContainerExceptionInterface
{
}
