<?php

namespace App\Domain\Import;

use PhpBench\Dom\Document;
use Elasticsearch\Client;
use App\Domain\Store\VariantStore;
use PhpBench\Dom\Element;
use App\Domain\Store\SuiteStore;
use App\Domain\Store\IterationStore;

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

    /**
     * @var IterationStore
     */
    private $iterationStore;

    public function __construct(VariantStore $variantStore, SuiteStore $suiteStore, IterationStore $iterationStore)
    {
        $this->variantStore = $variantStore;
        $this->suiteStore = $suiteStore;
        $this->iterationStore = $iterationStore;
    }

    public function import(Document $document)
    {
        $suiteUuid = null;

        foreach ($document->query('//suite') as $suiteDocument) {
            $suiteUuid = $suiteDocument->getAttribute('uuid');
            $this->storeSuite($suiteUuid, $suiteDocument);
            $this->storeVariants($suiteDocument);
            $this->storeIterations($suiteDocument);
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
                /** @var Element $variantDocument */
                foreach ($subjectDocument->query('.//variant') as $index => $variantDocument) {
                    $document = array_merge($document, $this->flattenDocument($variantDocument));
                    $document['variant-index'] = $index;
                    foreach ($variantDocument->query('.//stats') as $statsDocument) {
                        $document = array_merge($document, $this->flattenDocument($statsDocument));
                    }
                    $document['variant-iterations'] = $variantDocument->query('.//iteration')->length;

                    $identifier = $this->generateId($document);
                    $documents[$identifier] = $document;
                }
            }
        }

        $this->variantStore->store($identifier, $documents);
    }

    private function storeIterations(Element $suiteDocument)
    {
        $document = [
            'suite-uuid' => $suiteDocument->getAttribute('uuid'),
        ];
        foreach ($suiteDocument->query('.//benchmark') as $benchmarkDocument) {
            $document = array_merge($document, $this->flattenDocument($benchmarkDocument));
            foreach ($benchmarkDocument->query('.//subject') as $subjectDocument) {
                $document = array_merge($document, $this->flattenDocument($subjectDocument));
                foreach ($subjectDocument->query('.//variant') as $index => $variantDocument) {
                    $document['variant-index'] = $index;
                    $iterationNb = 0;
                    foreach ($variantDocument->query('.//iteration') as $iterationDocument) {
                        foreach ($iterationDocument->attributes as $attrName => $attrElement) {
                            $document[$attrName] = $attrElement->nodeValue;
                        }
                        $document['iteration'] = $iterationNb++;

                        $documents[] = $document;
                    }
                }
            }
        }

        $this->iterationStore->store(uniqid(), $documents);
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
