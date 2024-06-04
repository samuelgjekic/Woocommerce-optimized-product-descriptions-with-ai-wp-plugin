<?php

declare(strict_types=1);

namespace LLPhant;

class OllamaConfig
{
    public string $model;

    public string $url = '';

    public bool $stream = false;

    public bool $formatJson = false;

    /**
     * model options, example:
     * - options
     * - template
     * - raw
     * - keep_alive
     *
     * @see https://github.com/ollama/ollama/blob/main/docs/api.md#generate-a-completion
     *
     * @var array<string, mixed>
     */
    public array $modelOptions = [];
}
