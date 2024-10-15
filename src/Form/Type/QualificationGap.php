<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class QualificationGap extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'Qualification gaps (url of qualification results): ',
                'constraints' => new Regex(['pattern' => '^https://www\.thesimgrid\.com/championships/[0-9]+/results\?filter_class_id=[0-9]+&overall=(false|true)&race_id=[0-9]+&session_type=qualifying^'])
            ])
            ->add('analyse', SubmitType::class)
        ;
    }
}