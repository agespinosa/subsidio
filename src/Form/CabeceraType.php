<?php

namespace App\Form;

use App\Entity\Cabecera;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\TimeType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class CabeceraType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('registroId')
            ->add('fechaCreacionArchivo',DateType::class,  array(
                'constraints' => null,
                'data' => (isset($options['data']) &&
                    $options['data']->getFechaHabilProcesamiento() !== null) ? $options['data']->getFechaHabilProcesamiento() : new \DateTime(),
                // render as a single text box
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // do not render as type="date", to avoid HTML5 date pickers
                // 'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']
            ))
            ->add('horaCreacionArchivo', TimeType::class)
            ->add('numeroArchivo')
            ->add('numeroCliente')
            ->add('identificacionArchivo')
            ->add('fechaHabilProcesamiento',DateType::class,  array(
                'constraints' => null,
                'data' => (isset($options['data']) &&
                    $options['data']->getFechaHabilProcesamiento() !== null) ? $options['data']->getFechaHabilProcesamiento() : new \DateTime(),
                // render as a single text box
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // do not render as type="date", to avoid HTML5 date pickers
                // 'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']
            ))
            //->add('createdAt')
            //->add('updatedAt')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => Cabecera::class,
        ]);
    }
}
