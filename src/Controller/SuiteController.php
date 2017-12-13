<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Domain\Store\VariantStore;

class SuiteController
{
    /**
     * @var VariantStore
     */
    private $variantStore;

    public function __construct(VariantStore $variantStore)
    {
        $this->variantStore = $variantStore;
    }

    /**
     * @Route("/report/aggregate/suite/{uuid}", name="report_aggregate")
     */
    public function aggregate(Request $request)
    {
        $variants = $this->variantStore->forSuiteUuid($request->attributes->get('uuid'));

        return new Response('', 200);
    }
}
