<?php

declare(strict_types=1);

namespace Quickclack\RestAttributeBundle\Request;

use Symfony\Component\HttpFoundation\Request;
use Quickclack\RestAttributeBundle\Attribute\RouteParam;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class ParamFetcher
{
    private array $params = [];

    public function __construct(
        private Request $request,
        private ValidatorInterface $validator,
        private string $defaultErrorMessage = 'Invalid parameter value'
    ) {}

    public function fetch(array $paramAttributes): self
    {
        foreach ($paramAttributes as $param) {
            $value = match($param->from) {
                'query' => $this->request->query->get($param->name),
                'body' => $this->getBodyParam($param->name),
                'route' => $this->request->attributes->get($param->name),
                default => throw new \InvalidArgumentException("Unknown param source: {$param->from}")
            };

            $this->params[$param->name] = $this->processValue($param, $value);
        }

        return $this;
    }

    public function get(string $name): mixed
    {
        if (!array_key_exists($name, $this->params)) {
            throw new \InvalidArgumentException("Param '$name' not fetched");
        }
        return $this->params[$name];
    }

    public function getAll(): array
    {
        return $this->params;
    }

    private function getBodyParam(string $name): mixed
    {
        $content = $this->request->getContent();
        if (empty($content)) {
            return null;
        }

        $data = json_decode($content, true);
        if (json_last_error() !== JSON_ERROR_NONE) {
            throw new \RuntimeException('Invalid JSON body');
        }

        return $data[$name] ?? null;
    }

    private function processValue(RouteParam $param, mixed $value): mixed
    {
        // Если значение не передано и есть default
        if ($value === null && !$param->required) {
            return $param->default;
        }

        // Проверка на обязательность
        if ($param->required && $value === null) {
            throw new \RuntimeException($param->errorMessage ?? "Param '{$param->name}' is required");
        }

        // Приведение типа
        $value = $this->castValue($param->type, $value);

        // Валидация по паттерну
        if ($param->pattern && !preg_match($param->pattern, (string)$value)) {
            throw new \RuntimeException($param->errorMessage ?? "Invalid format for param '{$param->name}'");
        }

        return $value;
    }

    private function castValue(?string $type, mixed $value): mixed
    {
        if ($value === null) {
            return null;
        }

        return match($type) {
            'int' => (int)$value,
            'float' => (float)$value,
            'bool' => filter_var($value, FILTER_VALIDATE_BOOL),
            'array' => is_array($value) ? $value : json_decode($value, true) ?? [$value],
            default => $value
        };
    }
}
