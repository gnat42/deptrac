<?php

declare(strict_types=1);

namespace Qossmic\Deptrac\Core\Layer\Collector;

use LogicException;
use Qossmic\Deptrac\Contract\Ast\TokenReferenceInterface;
use Qossmic\Deptrac\Contract\Layer\CollectorInterface;
use Qossmic\Deptrac\Core\Ast\AstMap\AstMap;
use Qossmic\Deptrac\Core\Ast\AstMap\ClassLike\ClassLikeReference;
use function intval;
use function trigger_deprecation;

final class InheritanceLevelCollector implements CollectorInterface
{
    public function satisfy(array $config, TokenReferenceInterface $reference, AstMap $astMap): bool
    {
        if (!$reference instanceof ClassLikeReference) {
            return false;
        }

        $classInherits = $astMap->getClassInherits($reference->getToken());

        if (isset($config['level']) && !isset($config['value'])) {
            trigger_deprecation('qossmic/deptrac', '0.20.0', 'InheritanceLevelCollector should use the "value" key from this version');
            $config['value'] = $config['level'];
        }

        if (!isset($config['value']) || (0 === intval($config['value']) && 0 == $config['value'])) {
            throw new LogicException('InheritanceLevelCollector needs inheritance depth as int.');
        }

        foreach ($classInherits as $classInherit) {
            if (count($classInherit->getPath()) >= $config['value']) {
                return true;
            }
        }

        return false;
    }
}
