<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class EstudioFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime("now");
        $years = range($date->format('Y'), $date->format('Y') - 30);

        $builder->setAction($options['action'])
                ->add('titulo', 'text', array('label' => 'Título'))
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
        return 'form_estudio';
    }
}