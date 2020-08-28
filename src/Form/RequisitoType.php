<?php

namespace App\Form;

use App\Entity\Requisito;
use Doctrine\DBAL\Types\StringType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\DateType;
use Symfony\Component\Form\Extension\Core\Type\FileType;
use Symfony\Component\Form\Extension\Core\Type\NumberType;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Validator\Constraints\File;

class RequisitoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('fechaDesde',DateType::class,  array(
                'constraints' => null,
                'label' => 'Fecha Pago',
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
            ->add('tipoFormaPago', ChoiceType::class, [
                'required' => true,
                'choices'  => [
                    'Pago Proveedores' => 'Pago_Proveedores',
                    'Punta Caja' => 'Punta_Caja',
                    
                ]
            ])
            ->add('motivoPago', TextType::class, [
                'required' => true
            ])
            ->add('numeroArchivoPago', NumberType::class, [
                'required' => true
            ])
           // ->add('numeroReferenciaClienteFila', NumberType::class, [
           //     'required' => true
           // ])
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
            'data_class' => Requisito::class,
        ]);
    }
}
