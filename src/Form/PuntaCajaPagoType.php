<?php

namespace App\Form;

use App\Entity\PuntaCajaPago;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class PuntaCajaPagoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesde',DateType::class,  array(
                'constraints' => null,
                'label' => 'Fecha Pago Desde',
                'data' => (isset($options['data']) &&
                    $options['data']->getFechaDesde() !== null) ? $options['data']->getFechaDesde() : new \DateTime(),
                // render as a single text box
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // do not render as type="date", to avoid HTML5 date pickers
                // 'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']
            ))
            ->add('fechaHasta',DateType::class,  array(
                'constraints' => null,
                'label' => 'Fecha Pago Hasta',
                'data' => (isset($options['data']) &&
                    $options['data']->getFechaHasta() !== null) ? $options['data']->getFechaHasta() : new \DateTime(),
                // render as a single text box
                'widget' => 'single_text',
                'format' => 'dd/MM/yyyy',
                // do not render as type="date", to avoid HTML5 date pickers
                // 'html5' => false,
                // add a class that can be selected in JavaScript
                'attr' => ['class' => 'js-datepicker']
            ))
            ->add('motivoPago')
            ->add('tipoSoporte', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    'Pago Principal' => 'Pago_Principal',
                    'Pago Complementario' => 'Pago_Complementario'
                ]
            ])
            ->add('filename', FileType::class, [
                'label' => 'Excel Beneficiarios',
        
                'attr' => ['class' => 'file-upload-button'],
        
                // unmapped means that this field is not associated to any entity property
                'mapped' => false,
        
                // make it optional so you don't have to re-upload the PDF file
                // every time you edit the Product details
                'required' => true,
        
                // unmapped fields can't define their validation using annotations
                // in the associated entity, so you can use the PHP constraint classes
                'constraints' => [
                    new File([
                        'maxSize' => '999999990024k',
                        'mimeTypes' => [
                            'application/xls',
                            'application/xlsx',
                            'application/ods',
                            'application/vnd.ms-excel',
                            'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
                        ],
                        'mimeTypesMessage' => 'Por favor adjuntar formato excel o libre office',
                    ])
                ],
            ])
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => PuntaCajaPago::class,
        ]);
    }
}
