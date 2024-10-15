<?php

namespace App\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Validator\Constraints\Regex;
use Symfony\Component\Form\Extension\Core\Type\UrlType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class RaceConsistency extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options): void
    {
        $builder
            ->add('url', UrlType::class, [
                'label' => 'Race consistency (url of race laps results): ',
                'constraints' => new Regex(['pattern' => '^https://www\.thesimgrid\.com/championships/[0-9]+/results\?filter_class_id=[0-9]+&overall=(false|true)&race_id=[0-9]+&result_type=laps&session_type=race_[0-9]+^'])
            ])
            ->add('analyse', SubmitType::class)
        ;
    }
}