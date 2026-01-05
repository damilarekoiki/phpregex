<?php

declare(strict_types=1);

namespace Ten\Phpregex;

use BadMethodCallException;
use Closure;
use LogicException;
use Ten\Phpregex\Expressions\Booleans;
use Ten\Phpregex\Expressions\Contains;
use Ten\Phpregex\Expressions\Exactly;
use Ten\Phpregex\Expressions\Flags;
use Ten\Phpregex\Expressions\Helpers;
use Ten\Phpregex\Expressions\Positional;
use Ten\Phpregex\Expressions\Quantifiers;
use Ten\Phpregex\Expressions\Sequential;

final class Regex
{
    use Contains;
    use Positional;
    use Quantifiers;
    use Sequential;
    use Helpers;
    use Flags;
    use Booleans;
    use Exactly;

    /**
     * @var array<int, string>
     */
    private array $patterns = [];
    private ?string $masterPattern = null;

    /**
     * @var array<int, string>
     */
    private readonly array $magicMethods;

    private bool $fullStringMatch = false;
    private bool $isConsuming = false;

    public function __construct()
    {
        $this->magicMethods = [
            'or',
            'and',
        ];
    }

    public function __get(string $name): mixed
    {
        if (!in_array($name, $this->magicMethods)) {
            throw new LogicException("You cannot access \"{$name}\" property. Do you mean \"{$name}()\"?");
        }
        if (method_exists($this, $name) === false) {
            throw new BadMethodCallException("Method \"{$name}\" does not exist.");
        }

        return $this->{$name}();
    }

    /**
     * Start building a new regex.
     *
     * @param bool $fullStringMatch Whether to enforce a full string match.
     */
    public static function build(bool $fullStringMatch = false): self
    {
        $regex = new self();
        $regex->fullStringMatch = $fullStringMatch;
        return $regex;
    }

    /**
     * Checks if the regex matches the given subject.
     *
     * @param string $subject The string to search the pattern in.
     */
    public function matches(string $subject): bool
    {
        return (bool) preg_match($this->resolve(), $subject);
    }

    /**
     * Count the number of matches in the given subject.
     *
     * @param string $subject The string to search the pattern in.
     * @return int The number of matches found.
     */
    public function count(string $subject): int
    {
        return (int) preg_match_all($this->resolve(), $subject);
    }

    /**
     * Replaces matches in the subject with the given replacement.
     *
     * @param string $subject The string to search the pattern in.
     * @param string|callable(array<string>): string $replacement The replacement string or a callable for callback.
     * @throws LogicException If the replacement is not a string or callable.
     * @return string The resulting string after replacement.
     */
    public function replace(string $subject, string|callable $replacement): string
    {
        if ($replacement instanceof Closure || (is_object($replacement) && method_exists($replacement, '__invoke'))) {
            return (string) preg_replace_callback($this->resolve(), $replacement, $subject);
        }

        if (is_string($replacement)) {
            return (string) preg_replace($this->resolve(), $replacement, $subject);
        }

        throw new LogicException('Replacement must be a string or callable.');
    }

    /**
     * Add a raw pattern string to the regex.
     *
     * @param string $pattern The regex pattern string.
     * @param bool $consuming Whether this pattern consumes characters.
     */
    public function addPattern(string $pattern, bool $consuming = true): self
    {
        $this->patterns[] = $pattern;
        if ($consuming) {
            $this->isConsuming = true;
        }
        return $this;
    }

    /**
     * Overwrite all current patterns with a new one.
     *
     * @param string|Closure(Regex $regex): mixed $pattern The new regex pattern string.
     */
    public function overridePattern(string|Closure $pattern): void
    {
        if ($pattern instanceof Closure) {
            $regex = self::build();
            $pattern($regex);
            $pattern = $regex->getPattern();
        }
        $this->masterPattern = $pattern;
    }

    /**
     * Get the full regex string.
     *
     * @return string The full regex string.
     */
    public function get(): string
    {
        return $this->resolve();
    }

    /**
     * Get the inner regex pattern (without delimiters and flags).
     *
     * @return string The regex pattern.
     */
    public function getPattern(): string
    {
        if ($this->masterPattern) {
            return $this->masterPattern;
        }
        $pattern = implode('', $this->patterns);

        if ($this->fullStringMatch) {
            if (!$this->isConsuming) {
                $pattern .= '.*';
            }
            if (!str_starts_with($pattern, '^')) {
                $pattern = '^' . $pattern;
            }
            if (!str_ends_with($pattern, '$')) {
                $pattern .= '$';
            }
        }

        return $pattern;
    }

    /**
     * Wrap a set of patterns in a capturing group.
     *
     * @param Closure(Regex $regex): mixed $closure A closure that defines the patterns inside the group.
     */
    public function group(Closure $closure): self
    {
        $regex = Regex::build();
        $this->addPattern('(');
        $closure($regex);
        $this->addPattern($regex->getPattern());
        $this->addPattern(')');
        return $this;
    }

    /**
     * Check if no patterns have been added yet.
     *
     * @return bool True if no patterns are present.
     */
    public function isEmpty(): bool
    {
        return $this->patterns === [] && ($this->masterPattern === null || $this->masterPattern === '');
    }

    /**
     * Resolve a simple pattern from various subject types.
     *
     * @param string|int|Closure(Regex $regex): mixed $subject The subject to resolve.
     * @return string The resolved regex pattern string.
     */
    private function resolveSimplePattern(string|int|Closure $subject): string
    {
        if ($subject instanceof Closure) {
            $regex = self::build();
            $subject($regex);
            return $regex->getPattern();
        }

        return preg_quote((string) $subject, '/');
    }

    /**
     * Resolve the complete regex string with delimiters and flags.
     *
     * @return string The full regex string.
     */
    private function resolve(): string
    {
        return '/' . $this->getPattern() . '/' . implode('', array_unique($this->flags ?? []));
    }
}
