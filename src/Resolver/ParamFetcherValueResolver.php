<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\Resolver;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\RequestStack;
use Quickclack\RestAttributeBundle\Attribute\RouteParam;
use Quickclack\RestAttributeBundle\Request\ParamFetcher;
use Symfony\Component\Validator\Validator\ValidatorInterface;
use Symfony\Component\HttpKernel\Controller\ValueResolverInterface;
use Symfony\Component\HttpKernel\ControllerMetadata\ArgumentMetadata;

final class ParamFetcherValueResolver implements ValueResolverInterface
{
    public function __construct(
        private RequestStack $requestStack,
        private ValidatorInterface $validator,
        private string $defaultErrorMessage = 'Invalid parameter value'
    ) {
    }

    public function resolve(Request $request, ArgumentMetadata $argument): iterable
    {
        if ($argument->getType() !== ParamFetcher::class) {
            return [];
        }

        $fetcher = new ParamFetcher($request, $this->validator, $this->defaultErrorMessage);

        // Получаем Param-атрибуты метода
        $controller = $request->attributes->get('_controller');
        $method = is_array($controller)
            ? new \ReflectionMethod($controller[0], $controller[1])
            : new \ReflectionFunction($controller);

        $params = array_map(
            fn ($attr) => $attr->newInstance(),
            $method->getAttributes(RouteParam::class)
        );

        yield $fetcher->fetch($params);
    }
}