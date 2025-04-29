<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\Attribute;

use Attribute;
use Symfony\Component\HttpFoundation\Request;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class RouteParam
{
    public function __construct(
        public string $name,
        public string $from = 'query',
        public ?string $type = null,
        public mixed $default = null,
        public bool $required = true,
        public ?string $pattern = null,
        public ?string $errorMessage = null
    ) {
    }
}
