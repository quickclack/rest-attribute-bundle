# Quickclack RestAttributeBundle

Modern REST support for Symfony 6.4–7.2 using attributes. This bundle simplifies the implementation of RESTful APIs by using PHP attributes for route parameters, making your Symfony controllers cleaner and easier to maintain.

## Features

- **Automatic parameter fetching** from query, request, or route.
- **Custom validation** of parameters using Symfony Validator.
- Support for Symfony versions **6.4–7.2**.

## Installation

Install the bundle via Composer:

```bash
composer require quickclack/rest-attribute-bundle
```

## Symfony 6.4–7.2 Compatibility

This bundle supports Symfony 6.4 through 7.2, so make sure you have a compatible Symfony version installed. 

## Usage

The bundle provides a simple way to fetch parameters from request queries and route parameters with automatic validation.

## Example

```
namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Quickclack\RestAttributeBundle\Attribute\RouteParam;
use Quickclack\RestAttributeBundle\Request\ParamFetcher;

class TestController
{
    #[Get('/api/test')]
    #[RouteParam(name: 'name', from: 'query', type: 'string')]
    #[RouteParam(name: 'age', from: 'query', type: 'int', default: 0, required: false)]
    public function testAction(ParamFetcher $fetcher): JsonResponse
    {
        return new JsonResponse([
            'name' => $fetcher->get('name'),
            'age' => $fetcher->get('age')
        ]);
    }
}
```

## Testing

```bash
composer install
php bin/phpunit
```

## License
This bundle is licensed under the MIT License. See the LICENSE file for more details.

## Changelog
All notable changes to this project will be documented in the CHANGELOG.md.