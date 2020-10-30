<?php

namespace App\Form;

use App\Entity\User;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ProfileType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $roles =
            [
                'ROLE_ADMIN' => 'ROLE_ADMIN',
                'ROLE_TESORERIA'=> 'ROLE_TESORERIA',
                'ROLE_AGENTE' => 'ROLE_AGENTE'
            ];
        $builder
            ->add('email')
            ->add('roles',ChoiceType::class ,array(
                'attr'  =>  array('class' => 'form-control', 'style' => 'margin:5px 0;'),
                'choice_label'=> 'Roles',
                'choices' => $roles,
                'expanded' => true,
                'multiple' => true
            ))
            ->add('firstname')
            ->add('password')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => User::class,
        ]);
    }
}
