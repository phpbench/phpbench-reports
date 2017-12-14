<?php

namespace App\Domain\Import;

use PhpBench\Dom\Document;
use Elasticsearch\Client;
use App\Domain\Store\VariantStore;
use PhpBench\Dom\Element;
use App\Domain\Store\SuiteStore;

class Importer
{
    const DELIMITER = '-';

    /**
     * @var VariantStore
     */
    private $variantStore;

    /**
     * @var SuiteStore
     */
    private $suiteStore;

    public function __construct(VariantStore $variantStore, SuiteStore $suiteStore)
    {
        $this->variantStore = $variantStore;
        $this->suiteStore = $suiteStore;
    }

    public function import(Document $document)
    {
        $suiteUuid = null;

        foreach ($document->query('//suite') as $suiteDocument) {
            $suiteUuid = $suiteDocument->getAttribute('uuid');
            $this->storeSuite($suiteUuid, $suiteDocument);
            $this->storeVariants($suiteDocument);

        }

        if (null === $suiteUuid) {
            throw new \RuntimeException(
                'No suites found in document'
            );
        }

        return $suiteUuid;
    }

    private function storeSuite(string $identifier, $suiteDocument)
    {
        $document = $this->flattenDocument($suiteDocument);
        foreach ($suiteDocument->query('.//env/*') as $envDocument) {
            $document = array_merge($document, $this->flattenDocument($envDocument, 'env'));
        }

        $this->suiteStore->store($identifier, [ $document ]);
    }

    private function storeVariants($suiteDocument)
    {
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
