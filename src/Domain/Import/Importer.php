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
        $suiteUuid = null;

        foreach ($document->query('//suite') as $suiteDocument) {
            $document = $this->flattenDocument($suiteDocument);
            foreach ($suiteDocument->query('.//env/*') as $envDocument) {
                $document = array_merge($document, $this->flattenDocument($envDocument, 'env'));
            }
            foreach ($suiteDocument->query('.//benchmark') as $benchmarkDocument) {
                $document = array_merge($document, $this->flattenDocument($benchmarkDocument));
                foreach ($benchmarkDocument->query('.//subject') as $subjectDocument) {
                    $document = array_merge($document, $this->flattenDocument($subjectDocument));
                    foreach ($benchmarkDocument->query('.//variant') as $variantDocument) {
                        $document = array_merge($document, $this->flattenDocument($variantDocument));
                        foreach ($benchmarkDocument->query('.//stats') as $statsDocument) {
                            $document = array_merge($document, $this->flattenDocument($statsDocument));
                        }

                        $identifier = $this->generateId($document);
                        $documents[$identifier] = $document;
                    }
                }
            }

            $this->variantStore->store($identifier, $documents);

            $suiteUuid = $suiteDocument->getAttribute('uuid');
        }

        if (null === $suiteUuid) {
            throw new \RuntimeException(
                'No suites found in document'
            );
        }

        return $suiteUuid;
    }

    private function flattenDocument(Element $element, string $basePrefix = '')
    {
        $data = [];
        $prefix = $this->buildPrefix($basePrefix, $element->nodeName);

        foreach ($element->attributes as $attrName => $attrElement) {
            $key = $prefix . self::DELIMITER . $attrName;
            $data[$key] = $attrElement->nodeValue;
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
        $id = implode('-', [ $data['suite-uuid'], md5(serialize($data)) ]);
        return $id;
    }
}
