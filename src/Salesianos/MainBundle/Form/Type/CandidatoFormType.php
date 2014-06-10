<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class CandidatoFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime("now");
        $years = range($date->format('Y'), 1960);

        $builder->add('nombre', 'text', array('label' => 'Nombre'))
                ->add('apellidos', 'text', array('label' => 'Apellidos'))
                ->add('fecha_nac', 'date', array(
                                            'format' => 'dMy',
                                            'label' => 'Fecha de nacimiento',
                                            'years' => $years))
                ->add('sexo', 'choice', array(
                                            'choices'   => array('Hombre' => 'Hombre', 'Mujer' => 'Mujer')))
                ->add('email')
                ->add('poblacion', 'text', array('label' => 'Población'))          
                ->add('provincia', 'entity', array('class' => 'SalesianosMainBundle:Provincia',
                                            'property' => 'nombre',
                                            'label' => 'Provincia'))
                ->add('codigo_postal', 'text', array('label' => 'Código Postal'))
                ->add('telefono', 'text', array('label' => 'Teléfono'))
                ->add('facebook', 'text', array('label' => 'Mi Facebook',
                                                'required' => false))
                ->add('twitter', 'text', array('label' => 'Mi Twitter',
                                                'required' => false))
                ->add('blog', 'text', array('label' => 'Mi Blog',
                                                'required' => false))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_datos_candidato';
    }
}