<?php

declare(strict_types=1);

namespace Ten\Phpregex\Expressions;

trait Helpers
{
    public function email(): self
    {
        return $this->addPattern("[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}");
    }

    public function ipv4(): self
    {
        return $this->addPattern("(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])");
    }

    public function ipv6(): self
    {
        return $this->addPattern("(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}");
    }

    public function ip(): self
    {
        return $this->addPattern("(?:(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])|(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4})");
    }

    public function uuid(): self
    {
        return $this->addPattern("[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}");
    }

    public function url(): self
    {
        return $this->addPattern("https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)");
    }

    public function alpha(): self
    {
        return $this->addPattern("[a-zA-Z]+");
    }

    public function alphanumeric(): self
    {
        return $this->addPattern("[a-zA-Z0-9]+");
    }

    public function digits(): self
    {
        return $this->addPattern("[0-9]+");
    }

    public function hexColor(): self
    {
        return $this->addPattern("#(?:[0-9a-fA-F]{3}){1,2}");
    }

    public function slug(): self
    {
        return $this->addPattern("[a-z0-9]+(?:-[a-z0-9]+)*");
    }

    public function creditCard(): self
    {
        return $this->addPattern("(?:(?:\d{4}[ -]?){3}\d{4})");
    }

    public function ssn(): self
    {
        return $this->addPattern("\d{3}-\d{2}-\d{4}");
    }

    public function zipCode(): self
    {
        return $this->addPattern("\d{5}(?:-\d{4})?");
    }

    public function macAddress(): self
    {
        return $this->addPattern("(?:[0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}");
    }

    public function date(): self
    {
        return $this->addPattern("\d{4}-\d{2}-\d{2}");
    }

    public function time(): self
    {
        return $this->addPattern("[0-2]\\d:[0-5]\\d:[0-5]\\d");
    }

    public function handle(): self
    {
        return $this->addPattern("@[a-zA-Z0-9_]{1,15}");
    }

    public function hex(): self
    {
        return $this->addPattern("[a-fA-F0-9]+");
    }
}
