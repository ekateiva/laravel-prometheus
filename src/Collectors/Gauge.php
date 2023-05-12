<?php

namespace Spatie\Prometheus\Collectors;

use Illuminate\Support\Facades\App;
use Illuminate\Support\Str;
use Prometheus\CollectorRegistry;

class Gauge implements Collector
{
    public function __construct(
        protected string $label,
        protected ?string $name = null,
        protected ?string $namespace = null,
        protected ?string $helpText = null,
        protected mixed $value = null,
    )
    {
        $this->name = $name ?? Str::slug($this->label, '_');

        $this->namespace = strtolower(App::getNamespace());
    }

    public function namespace(string $namespace): self
    {
        $this->namespace = $namespace;

        return $this;
    }

    public function name(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function helpText(string $helpText): self
    {
        $this->helpText = $helpText;

        return $this;
    }

    public function value(mixed $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function register(CollectorRegistry $registry): self
    {
        $gauge = $registry->getOrRegisterGauge(
            $this->namespace,
            $this->name,
            $this->helpText,
        );

        $gauge->set(value($this->value));

        return $this;
    }
}