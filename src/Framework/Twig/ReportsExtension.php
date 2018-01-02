<?php

namespace App\Framework\Twig;

use Twig\Extension\AbstractExtension;
use Twig\TwigFilter;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Twig\TwigFunction;

class ReportsExtension extends AbstractExtension
{
    /**
     * @var UrlGeneratorInterface
     */
    private $urlGenerator;

    public function __construct(UrlGeneratorInterface $urlGenerator)
    {
        $this->urlGenerator = $urlGenerator;
    }

    public function getFilters()
    {
        return array(
            new TwigFilter('short_class', [$this, 'shortClass']),
            new TwigFilter('reverse_truncate', [$this, 'reverseTruncate']),
        );
    }

    public function getFunctions()
    {
        return array(
            new TwigFunction('breadcrumb', [ $this, 'breadcrumb' ], [
                'is_safe' => [ 'html' ],
            ]),
        );
    }

    public function reverseTruncate(string $string, int $length): string
    {
        if (strlen($string) <= $length) {
            return $string;
        }

        return '...' . substr($string, -($length + 3));
    }

    public function shortClass($className)
    {
        $parts = explode('\\', $className);
        if (count($parts) === 1) {
            return $className;
        }

        return end($parts);
    }

    public function breadcrumb(array $segments)
    {
        $breadcrumb = [];
        $total = count($segments) - 1;

        foreach ($segments as $index => $segment) {
            if (!isset($segment['route']) || $total == $index) {
                $breadcrumb[] = $segment['label'];
                continue;
            }

            $breadcrumb[] = sprintf(
                '<a href="%s">%s</a>',
                $this->urlGenerator->generate(
                    $segment['route'],
                    $segment['params']
                ),
                $segment['label']
            );
        }

        $divider = '<div class="divider"> / </div>';

        return implode($divider, $breadcrumb);
    }
}
