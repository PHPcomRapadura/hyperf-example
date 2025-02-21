<?php

declare(strict_types=1);

namespace App\Infrastructure\Support\Presentation;

use Hyperf\Validation\Request\FormRequest;
use Psr\Container\ContainerInterface;

use function Hyperf\Collection\data_get;

/**
 * @see https://hyperf.wiki/3.1/#/en/validation?id=form-request-validation
 */
abstract class ActionInput extends FormRequest
{
    public function __construct(
        ContainerInterface $container,
        private ?array $values = null,
    ) {
        parent::__construct($container);
    }

    public function authorize(): bool
    {
        return true;
    }

    /**
     * @deprecated Use `value(string $key, mixed $default = null): mixed` instead
     */
    final public function input(string $key, mixed $default = null): mixed
    {
        return $this->value($key, $default);
    }

    /**
     * @deprecated Use `value(string $key, mixed $default = null): mixed` instead
     */
    public function post(?string $key = null, mixed $default = null): mixed
    {
        if (! $key) {
            return $this->values();
        }
        return $this->value($key, $default);
    }

    /**
     * @template T of mixed
     * @param T $default
     *
     * @return T
     */
    final public function value(string $key, mixed $default = null): mixed
    {
        return data_get($this->values(), $key, $default);
    }

    final public function values(): array
    {
        if (! $this->values) {
            $this->values = $this->validated();
        }
        return $this->values;
    }

    protected function validationData(): array
    {
        $data = parent::validationData();
        $params = $this->extractParams($data);
        return array_merge($data, $params);
    }

    private function extractParams(array $data): array
    {
        $keys = array_keys($this->rules());
        $params = [];
        foreach ($keys as $key) {
            if (isset($data[$key])) {
                continue;
            }
            $param = $this->route($key);
            if ($param) {
                $params[$key] = $param;
            }
        }
        return $params;
    }
}
