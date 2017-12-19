<?php

namespace App\Tests\Acceptance;

use Behat\Behat\Context\Context;
use Behat\MinkExtension\Context\RawMinkContext;
use PHPUnit\Framework\Assert;

class FormContext extends RawMinkContext implements Context
{
    /**
     * @Then I should see a form error message :message
     */
    public function iShouldSeeAFormErrorMessage($message)
    {
        $elements = $this->getSession()->getPage()->findAll('css', '.ac-error-message');

        foreach ($elements as $element) {
            if (preg_match('{' . $message . '}', $element->getHtml())) {
                return;
            }
        }

        Assert::fail('Could not find error message');
    }
}
