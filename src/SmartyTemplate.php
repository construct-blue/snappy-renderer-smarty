<?php

declare(strict_types=1);

namespace Blue\Snappy\Renderer\Strategy\Smarty;

class SmartyTemplate
{
    private array $vars = [];

    public function __construct(private readonly string $file)
    {
    }

    /**
     * @param string $key
     * @param $value
     * @return $this
     */
    public function assign(string $key, $value): self
    {
        $this->vars[$key] = $value;
        return $this;
    }

    /**
     * @return array
     */
    public function getVars(): array
    {
        return $this->vars;
    }

    /**
     * @return string
     */
    public function getFile(): string
    {
        return $this->file;
    }
}