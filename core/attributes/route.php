<?php declare(strict_types=1);
/*
 * The MIT License
 *
 * Copyright 2020 Leonid Kuzin(Dg_INC) <dg.inc.lcf@gmail.com>.
 *
 * Permission is hereby granted, free of charge, to any person obtaining a copy
 * of this software and associated documentation files (the "Software"), to deal
 * in the Software without restriction, including without limitation the rights
 * to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
 * copies of the Software, and to permit persons to whom the Software is
 * furnished to do so, subject to the following conditions:
 *
 * The above copyright notice and this permission notice shall be included in
 * all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 * IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
 * FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
 * AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
 * LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
 * OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
 * THE SOFTWARE.
 */

namespace Core\Attributes;

use Attribute;

#[Attribute(Attribute::IS_REPEATABLE | Attribute::TARGET_CLASS | Attribute::TARGET_METHOD)]
final class Route
{
    /**
     * Default regular expression when none is defined in the parameter
     */
    public const DEFAULT_REGEX = '[\w\-]+';

    /**
     * @var array $parameters Keeps the parameters cached with the associated regex
     */
    private array $parameters = [];

    public function __construct(
        private string $path,
        private string $name = '',
        private array $methods = ['GET'],
    ) {
        if (empty($this->name)) {
            $this->name = $this->path;
        }
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @return array
     */
    public function getMethods(): array
    {
        return $this->methods;
    }

    /**
     * Checks the presence of parameters in the path of the route
     *
     * @return bool
     */
    public function hasParams(): bool
    {
        return preg_match('/{([\w\-%]+)(<(.+)>)?}/', $this->path);
    }

    /**
     * Retrieves in key of the array, the names of the parameters as well as the regular expression (if there is one)
     * in value
     *
     * @return array
     */
    public function fetchParams(): array
    {
        if (empty($this->parameters)) {
            preg_match_all('/{([\w\-%]+)(?:<(.+?)>)?}/', $this->getPath(), $params);
            $this->parameters = array_combine($params[1], $params[2]);
        }

        return $this->parameters;
    }
}