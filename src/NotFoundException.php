<?php declare(strict_types=1);

namespace Xynha\Container;

use Exception;
use Psr\Container\NotFoundExceptionInterface;

final class NotFoundException extends Exception implements NotFoundExceptionInterface
{

}
