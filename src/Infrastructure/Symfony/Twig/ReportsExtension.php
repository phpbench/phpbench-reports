<?php

namespace App\Infrastructure\Symfony\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;

class ReportsExtension extends AbstractExtension
{
    public function getFilters()
    {
        return array(
            new TwigFilter('short_class', array($this, 'shortClass')),
        );
    }

    public function shortClass($className)
    {
        $parts = explode('\\', $className);
        if (count($parts) === 1) {
            return $className;
        }

        return end($parts);
    }
}
