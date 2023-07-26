<?php
declare(strict_types=1);

namespace Core\Include\Attributes;

use Attribute;

#[Attribute(Attribute::TARGET_CLASS|Attribute::IS_REPEATABLE)]
class Instances {
    public function __construct(string $scope, string|array $methods) {
    }
}