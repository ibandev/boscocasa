<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class ConocimientoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->setAction($options['action'])
                ->add('conocimiento', 'text', array('label' => 'Conocimiento'))
                ->add('nivel', 'choice', array(
                                        'choices'   => array('Alto' => 'Alto', 'Medio' => 'Medio', 'Bajo' => 'Bajo')
                                        ))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_conocimiento';
    }
}