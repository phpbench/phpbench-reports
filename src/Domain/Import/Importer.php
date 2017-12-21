<?php

namespace App\Domain\Import;

use PhpBench\Dom\Document;
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

            // only one suite supported per file
            break;
        }

        if (null === $suiteUuid) {
            throw new \RuntimeException(
                'No suites found in document'
            );
        }

        return $suiteUuid;
    }

    private function storeSuite(string $identifier, Element $suiteDocument)
    {
        $document = $this->flattenDocument($suiteDocument);
        $document['project-id'] = $suiteDocument->parentNode->getAttribute('project-id');
        $document['project'] = $suiteDocument->parentNode->getAttribute('project');
        $document['project-namespace'] = $suiteDocument->parentNode->getAttribute('project-namespace');
        $document['project-name'] = $suiteDocument->parentNode->getAttribute('project-name');
        foreach ($suiteDocument->query('.//env/*') as $envDocument) {
            $document = array_merge($document, $this->flattenDocument($envDocument, 'env'));
        }

        $this->suiteStore->store($identifier, [ $document ]);
    }

    private function storeVariants(Element $suiteElement)
    {
        $document = $this->flattenDocument($suiteElement);
        foreach ($suiteElement->query('.//env/*') as $envElement) {
            $document = array_merge($document, $this->flattenDocument($envElement, 'env'));
        }
        foreach ($suiteElement->query('.//benchmark') as $benchmarkElement) {
            $benchDocument = array_merge($document, $this->flattenDocument($benchmarkElement));
            foreach ($benchmarkElement->query('.//subject') as $subjectElement) {
                $subjectDocument = array_merge($benchDocument, $this->flattenDocument($subjectElement));
                /** @var Element $variantDocument */
                foreach ($subjectElement->query('.//variant') as $index => $variantElement) {
                    $variantDocument = array_merge($subjectDocument, $this->flattenDocument($variantElement));
                    $variantDocument['variant-index'] = $index;
                    foreach ($variantElement->query('.//stats') as $statsElement) {
                        $variantDocument = array_merge($variantDocument, $this->flattenDocument($statsElement));
                    }
                    $variantDocument['variant-iterations'] = $variantElement->query('.//iteration')->length;

                    $identifier = $this->generateId($document);
                    $documents[] = $variantDocument;
                }
            }
        }

        $this->variantStore->storeMany($documents);
    }

    private function storeIterations(Element $suiteElement)
    {
        $documents = [];
        $document = [
            'suite-uuid' => $suiteElement->getAttribute('uuid'),
        ];
        foreach ($suiteElement->query('.//benchmark') as $benchmarkElement) {
            $benchDocument = array_merge($document, $this->flattenDocument($benchmarkElement));
            foreach ($benchmarkElement->query('.//subject') as $subjectElement) {
                $subjectDocument = array_merge($benchDocument, $this->flattenDocument($subjectElement));
                foreach ($subjectElement->query('.//variant') as $index => $variantElement) {
                    $variantDocument = $subjectDocument;
                    $variantDocument['variant-index'] = $index;
                    $iterationNb = 0;
                    foreach ($variantElement->query('.//iteration') as $iterationElement) {
                        $iterationDocument = $variantDocument;
                        foreach ($iterationElement->attributes as $attrName => $attrElement) {
                            $iterationDocument[$attrName] = $attrElement->nodeValue;
                        }
                        $iterationDocument['iteration'] = $iterationNb++;

                        $documents[] = $iterationDocument;
                    }
                }
            }
        }

        $this->iterationStore->storeMany($documents);
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
