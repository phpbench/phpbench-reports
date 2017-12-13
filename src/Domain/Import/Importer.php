<?php

namespace App\Domain\Import;

use PhpBench\Dom\Document;
use Elasticsearch\Client;
use App\Domain\Store\VariantStore;
use PhpBench\Dom\Element;

class Importer
{
    const DELIMITER = '-';

    /**
     * @var VariantStore
     */
    private $variantStore;

    public function __construct(VariantStore $variantStore)
    {
        $this->variantStore = $variantStore;
    }

    public function import(Document $document)
    {
        $data = $this->flattenDocument($document->firstChild);
        $this->variantStore->store($this->generateId($data), $data);
    }

    private function flattenDocument(Element $element, string $basePrefix = '')
    {
        $data = [];
        /**  @var Element $element */
        foreach ($element->query('./*[not(self::result)]') as $element) {
            $prefix = $this->buildPrefix($basePrefix, $element->nodeName);

            foreach ($element->attributes as $attrName => $attrElement) {
                $key = $prefix . self::DELIMITER . $attrName;
                $data[$key] = $attrElement->nodeValue;
            }

            $data = array_merge($data, $this->flattenDocument($element, $prefix));
        }

        return $data;
    }

    private function buildPrefix(...$elements)
    {
        if ($elements[0] === 'suite') {
            array_shift($elements);
        }

        return implode(self::DELIMITER, array_filter($elements));
    }

    private function generateId(array $data)
    {
        $id = implode('-', [ $data['suite-uuid'], $data['benchmark-class'], $data['benchmark-subject-name'] ]);
        return $id;
    }
}
