<?php

namespace Nexus633\BootstrapUi\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\HtmlString;
use Illuminate\View\ComponentAttributeBag;

class BootstrapUi
{
    private array $classes = [];
    private array $styles = [];
    private array $dataAttributes = [];

    // Specific prefixes must come before general ones (e.g., 'px-' before 'p-')
    private const PREFIXES = [
        'fs-', 'text-', 'bg-', 'align-items-', 'justify-content-', 'flex-',
        'px-', 'py-', 'ps-', 'pe-', 'pt-', 'pb-', 'p-',
        'mx-', 'my-', 'ms-', 'me-', 'mt-', 'mb-', 'm-',
        'gx-', 'gy-', 'g-', 'gap-',
        'd-', 'border-', 'rounded-', 'order-', 'col-', 'row-cols-'
    ];

    public function __construct(
        private ?string $name
    ) {}

    public function hasError(): bool
    {
        if (empty($this->name) || !session()->has('errors')) {
            return false;
        }

        $errors = session()->get('errors');

        return $errors->has($this->name) || $errors->has($this->name . '.*');
    }

    public function addClass(string ...$classes): self
    {
        foreach ($classes as $class) {
            if (empty($class)) {
                continue;
            }

            $this->removeClassesByPrefix($class);

            $this->classes[] = $class;
        }

        return $this;
    }

    public function addClassWhen(mixed $condition, string|array|null $classes, string|array|null $elseClasses = null): self
    {
        if ($condition) {
            $this->addClass(...(array)$classes);
        } elseif ($elseClasses !== null) {
            $this->addClass(...(array)$elseClasses);
        }
        return $this;
    }

    public function getClasses(): ?string
    {
        $classString = implode(' ', array_unique($this->classes));
        if(empty($classString)){
            return null;
        }
        return $classString;
    }

    public function addStyle(string $property, string $value): self
    {
        $this->styles[$property] = $value;
        return $this;
    }

    public function addStyleWhen(mixed $condition, string $property, ?string $value): self
    {
        if ($condition) {
            $this->addStyle($property, $value);
        }
        return $this;
    }

    public function getStyles(): ?string
    {
        if (empty($this->styles)) {
            return null;
        }

        $stylePairs = [];
        foreach ($this->styles as $property => $value) {
            $stylePairs[] = "{$property}: {$value};";
        }

        return implode(' ', $stylePairs);
    }

    public function addData(string $name, string $value): self
    {
        $this->dataAttributes[$name] = $value;
        return $this;
    }

    public function addDataWhen(mixed $condition, string $name, ?string $value, ?string $elseValue = null): self
    {
        if ($condition) {
            $this->addData($name, $value);
        } elseif ($elseValue !== null) {
            $this->addData($name, $elseValue);
        }

        return $this;
    }

    public function getDataAttributes(): array
    {
        return $this->dataAttributes;
    }

    public function getDataAttributeString(): string
    {
        $html = collect($this->dataAttributes)
            ->filter(fn($v) => $v !== null && $v !== false)
            ->map(function($value, $key) {
                // Boolean
                if ($value === true) return e($key);

                // Array / Objekt → JSON
                if (is_array($value) || is_object($value)) {
                    $value = json_encode($value, JSON_THROW_ON_ERROR);
                }

                return sprintf('%s="%s"', e($key), e((string)$value));
            })
            ->implode(' ');

        return new HtmlString($html);
    }

    private function removeClassesByPrefix(string $newClass): void
    {
        $prefixFound = null;
        foreach (self::PREFIXES as $prefix) {
            if (str_starts_with($newClass, $prefix)) {
                $prefixFound = $prefix;
                break;
            }
        }

        if ($prefixFound) {
            $this->classes = array_filter($this->classes, function ($existingClass) use ($prefixFound) {
                return !str_starts_with($existingClass, $prefixFound);
            });
        }
    }

    public function clearStyles(): void
    {
        $this->styles = [];
    }

    public function clearClasses(): void
    {
        $this->classes = [];
    }

    public function clearDataAttributes(): void
    {
        $this->dataAttributes = [];
    }

    public function clear(): void
    {
        $this->clearStyles();
        $this->clearClasses();
        $this->clearDataAttributes();
    }
}
