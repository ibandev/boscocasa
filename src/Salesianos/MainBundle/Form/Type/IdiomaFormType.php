<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class IdiomaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->setAction($options['action'])
                ->add('idioma', 'text', array('label' => 'Idioma'))
                ->add('nivel', 'choice', array(
                                        'choices'   => array('Alto' => 'Alto', 'Medio' => 'Medio', 'Bajo' => 'Bajo')
                                        ))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_idioma';
    }
}