<?php

namespace App\Framework\Validator;

use Symfony\Component\Validator\Constraint;

class Unique extends Constraint
{
    public $entityClass;
    public $fields = [];
}
