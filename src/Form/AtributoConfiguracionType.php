<?php

namespace App\Form;

use App\Entity\AtributoConfiguracion;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class AtributoConfiguracionType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('clave')
            ->add('valor')
            ->add('fechaBaja',DateType::class,  array(
                'constraints' => null,
                'label' => 'Fecha Baja',
                'data' => (isset($options['data']) &&
                    $options['data']->getFechaBaja() !== null) ? $options['data']->getFechaBaja() : null,
                // render as a single text box
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                'required' => false,
                // do not render as type="date", to avoid HTML5 date pickers
                // 'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']
            ))
           
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => AtributoConfiguracion::class,
        ]);
    }
}
