<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Core\Layer\Collector;

use Qossmic\Deptrac\Contract\Layer\CollectorInterface;

interface ConditionalCollectorInterface extends CollectorInterface
{
    /**
     * @param array<string, bool|string|array<string, string>> $config
     */
    public function resolvable(array $config): bool;
}
