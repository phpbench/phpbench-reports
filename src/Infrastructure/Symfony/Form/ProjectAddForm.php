<?php

namespace App\Infrastructure\Symfony\Form;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\NotBlank;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\ButtonType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\OptionsResolver\OptionsResolver;
use App\Infrastructure\Doctrine\Entity\DoctrineUser;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use App\Infrastructure\Symfony\Form\ProjectDto;
use App\Infrastructure\Symfony\Validator\Unique;
use App\Infrastructure\Doctrine\Entity\DoctrineProject;
use Symfony\Component\Validator\Constraints\Regex;

class ProjectAddForm extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('namespace', TextType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
            ],
            'disabled' => true,
        ]);

        $builder->add('name', TextType::class, [
            'required' => true,
            'constraints' => [
                new NotBlank(),
                new Regex('{[a-zA-Z0-9]+}'),
            ],
        ]);

        $builder->add('Add Project', SubmitType::class, [
        ]);
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefault('constraints', [
            new Unique([
                'fields' => [ 'namespace', 'name' ],
                'entityClass' => DoctrineProject::class,
            ])
        ]);
    }
}
