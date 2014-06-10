<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class CurriculumFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('titulaciones', 'text', array('label' => 'Titulación'))
                ->add('experiencia', 'text', array('label' => 'Experiencia'))
                ->add('conocimientos', 'text', array('label' => 'Conocimientos'))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_curriculum';
    }
}