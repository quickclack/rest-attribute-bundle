<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\Attribute;

use Attribute;
use Symfony\Component\Routing\Attribute\Route;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Delete extends Route
{
    public function __construct(
        public string $path,
        public ?string $name = null,
        public array $requirements = [],
        public array $options = [],
        public array $defaults = [],
        public ?string $host = null,
        public array|string $methods = ['DELETE']
    ) {
        parent::__construct($path, $name, $requirements, $options, $defaults, $host, $methods);
    }
}
