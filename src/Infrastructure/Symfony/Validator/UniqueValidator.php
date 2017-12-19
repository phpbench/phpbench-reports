<?php

namespace App\Infrastructure\Symfony\Validator;

use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Validator\ConstraintValidator;
use Symfony\Component\Validator\Constraint;

class UniqueValidator extends ConstraintValidator
{
    /**
     * @var EntityManagerInterface
     */
    private $entityManager;

    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
    }

    /**
     * {@inheritDoc}
     */
    public function validate($dto, Constraint $constraint)
    {
        $repository = $this->entityManager->getRepository($constraint->entityClass);
        $classMetadata = $this->entityManager->getClassMetadata($constraint->entityClass);

        $criteria = [];
        foreach ($constraint->fields as $field) {
            
            $criteria[$field] = $dto->$field;
        }

        if ($repository->findOneBy($criteria)) {
            $this->context->buildViolation('Project already exists')
                ->addViolation();
        }

    }
}
