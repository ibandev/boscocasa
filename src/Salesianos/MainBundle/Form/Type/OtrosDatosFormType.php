<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class OtrosDatosFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('carnet', 'choice', array('label' => 'Permiso de conducir',
                                                'choices' => array(true => 'Sí', false => 'No')))
                ->add('vehiculo', 'choice', array('label' => 'Vehículo propio',
                                                    'choices' => array(true => 'Sí', false => 'No')))
                ->add('movilidad', 'choice', array('label' => 'Movilidad geográfica',
                                                    'choices' => array(true => 'Sí', false => 'No')))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_otros_datos';
    }
}