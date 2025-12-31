<?php

namespace Ten\Phpregex\Expressions;

trait Helpers
{
    public function email(): self
    {
        $this->patterns[] = "[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}";
        return $this;
    }

    public function ipv4(): self
    {
        $this->patterns[] = "(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])";
        return $this;
    }

    public function ipv6(): self
    {
        $this->patterns[] = "(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4}";
        return $this;
    }

    public function ip(): self
    {
        $this->patterns[] = "(?:(?:(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])\.){3}(?:\d|[1-9]\d|1\d\d|2[0-4]\d|25[0-5])|(?:[a-fA-F0-9]{1,4}:){7}[a-fA-F0-9]{1,4})";
        return $this;
    }

    public function uuid(): self
    {
        $this->patterns[] = "[0-9a-fA-F]{8}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{4}-[0-9a-fA-F]{12}";
        return $this;
    }

    public function url(): self
    {
        $this->patterns[] = "https?:\/\/(?:www\.)?[-a-zA-Z0-9@:%._\+~#=]{1,256}\.[a-zA-Z0-9()]{1,6}\b(?:[-a-zA-Z0-9()@:%_\+.~#?&\/=]*)";
        return $this;
    }

    public function alpha(): self
    {
        $this->patterns[] = "[a-zA-Z]+";
        return $this;
    }

    public function alphanumeric(): self
    {
        $this->patterns[] = "[a-zA-Z0-9]+";
        return $this;
    }

    public function digits(): self
    {
        $this->patterns[] = "[0-9]+";
        return $this;
    }

    public function hexColor(): self
    {
        $this->patterns[] = "#(?:[0-9a-fA-F]{3}){1,2}";
        return $this;
    }

    public function slug(): self
    {
        $this->patterns[] = "[a-z0-9]+(?:-[a-z0-9]+)*";
        return $this;
    }

    public function creditCard(): self
    {
        $this->patterns[] = "(?:(?:\d{4}[ -]?){3}\d{4})";
        return $this;
    }

    public function ssn(): self
    {
        $this->patterns[] = "\d{3}-\d{2}-\d{4}";
        return $this;
    }

    public function zipCode(): self
    {
        $this->patterns[] = "\d{5}(?:-\d{4})?";
        return $this;
    }

    public function macAddress(): self
    {
        $this->patterns[] = "(?:[0-9a-fA-F]{2}[:-]){5}[0-9a-fA-F]{2}";
        return $this;
    }

    public function date(): self
    {
        $this->patterns[] = "\d{4}-\d{2}-\d{2}";
        return $this;
    }

    public function time(): self
    {
        $this->patterns[] = "[0-2][0-9]:[0-5][0-9]:[0-5][0-9]";
        return $this;
    }

    public function handle(): self
    {
        $this->patterns[] = "@[a-zA-Z0-9_]{1,15}";
        return $this;
    }

    public function hex(): self
    {
        $this->patterns[] = "[a-fA-F0-9]+";
        return $this;
    }
}
