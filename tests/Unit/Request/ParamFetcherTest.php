<?php

namespace Unit\Request;

use PHPUnit\Framework\TestCase;
use Quickclack\RestAttributeBundle\Attribute\RouteParam;
use Quickclack\RestAttributeBundle\Request\ParamFetcher;
use Symfony\Component\HttpFoundation\Request;

class ParamFetcherTest extends TestCase
{
    public function testFetchQueryParam(): void
    {
        $request = Request::create('/?name=test');

        $paramFetcher = new ParamFetcher($request);

        $param = new RouteParam(
            name: 'name',
            from: 'query',
            type: 'string',
            required: true
        );

        $paramFetcher->fetch([$param]);

        $this->assertEquals('test', $paramFetcher->get('name'));
    }

    public function testRequiredParamMissing(): void
    {
        $request = Request::create('/');

        $paramFetcher = new ParamFetcher($request);

        $param = new RouteParam(
            name: 'id',
            from: 'query',
            type: 'int',
            required: true
        );

        $this->expectException(\RuntimeException::class);
        $paramFetcher->fetch([$param]);
    }

    public function testTypeConversion(): void
    {
        $request = Request::create('/?id=123&active=true');

        $paramFetcher = new ParamFetcher($request);

        $params = [
            new RouteParam(name: 'id', from: 'query', type: 'int'),
            new RouteParam(name: 'active', from: 'query', type: 'bool')
        ];

        $paramFetcher->fetch($params);

        $this->assertSame(123, $paramFetcher->get('id'));
        $this->assertSame(true, $paramFetcher->get('active'));
        $this->assertIsInt($paramFetcher->get('id'));
        $this->assertIsBool($paramFetcher->get('active'));
    }

    public function testDefaultValue(): void
    {
        $request = Request::create('/');

        $paramFetcher = new ParamFetcher($request);

        $param = new RouteParam(
            name: 'page',
            from: 'query',
            type: 'int',
            default: 1,
            required: false
        );

        $paramFetcher->fetch([$param]);

        $this->assertEquals(1, $paramFetcher->get('page'));
    }

    public function testBodyParam(): void
    {
        $request = Request::create(
            '/',
            'POST',
            [],
            [],
            [],
            ['CONTENT_TYPE' => 'application/json'],
            json_encode(['user' => ['name' => 'John']])
        );

        $paramFetcher = new ParamFetcher($request);

        $param = new RouteParam(
            name: 'user',
            from: 'body',
            type: 'array'
        );

        $paramFetcher->fetch([$param]);

        $this->assertEquals(['name' => 'John'], $paramFetcher->get('user'));
    }
}