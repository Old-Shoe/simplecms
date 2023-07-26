<?php

declare(strict_types=1);

namespace Core\Include;

interface ExtensionInterface {
    public static function install(): void;
    public static function update(): void;
    public static function uninstall(): void;
}