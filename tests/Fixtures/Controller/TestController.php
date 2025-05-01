<?php

namespace Quickclack\RestAttributeBundle\Tests\Fixtures\Controller;


use Symfony\Component\HttpFoundation\JsonResponse;
use Quickclack\RestAttributeBundle\Attribute\Get;
use Quickclack\RestAttributeBundle\Attribute\RouteParam;
use Quickclack\RestAttributeBundle\Request\ParamFetcher;

class TestController
{
    #[Get('/api/test')]
    #[RouteParam(name: 'name', from: 'query', type: 'string')]
    #[RouteParam(name: 'age', from: 'query', type: 'int', default: 0, required: true, pattern: '\d+')]
    public function testAction(ParamFetcher $fetcher): JsonResponse
    {
        return new JsonResponse([
            'name' => $fetcher->get('name'),
            'age' => $fetcher->get('age')
        ]);
    }
}