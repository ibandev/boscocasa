<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class ExperienciaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $date = new \DateTime("now");
        $years = range($date->format('Y'), $date->format('Y') - 30);

        $builder->setAction($options['action'])
                ->add('puesto', 'text', array('label' => 'Puesto'))
                ->add('empresa', 'text', array('label' => 'Empresa'))
                ->add('fecha_ini', 'date', array(
                                        'format' => 'dMy',
                                        'data' => new \DateTime("now"),
                                        'label' => 'Fecha inicio',
                                        'years' => $years))
                ->add('fecha_fin', 'date', array(
                                        'format' => 'dMy',
                                        'data' => new \DateTime("now"),
                                        'label' => 'Fecha fin',
                                        'years' => $years))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_experiencia';
    }
}