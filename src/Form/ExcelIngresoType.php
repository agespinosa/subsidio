<?php

namespace App\Form;

use App\Entity\ExcelIngreso;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ExcelIngresoType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('nombre')
            ->add('apellido')
            ->add('dni')
            ->add('cuit')
            ->add('regimenIva')
            ->add('categoriaIva')
            ->add('email')
            ->add('cbu')
            ->add('tipoCuenta')
            ->add('banco')
            ->add('monto')
            ->add('rubro')
            ->add('numeroCuentaBancaria')
            ->add('estado')
            ->add('createdAt')
            ->add('updatedAt')
            ->add('requisito')
        ;
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => ExcelIngreso::class,
        ]);
    }
}
