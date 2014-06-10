<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class OfertaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $date = new \DateTime("now");
        $years = range($date->format('y'), $date->format('y') + 1);

        $builder
            ->setAction('publicaroferta')
            ->add('puesto', 'text')
            ->add('sector', 'entity', array('class' => 'SalesianosMainBundle:Sector',
                                            'property' => 'nombre',
                                            'label' => 'Sector'))
            ->add('fecha_fin', 'date', array(
                                        'format' => 'dMy',
                                        'data' => new \DateTime("now"),
                                        'label' => 'Fecha de finalización',
                                        'years' => $years))
            ->add('poblacion', 'text', array('label' => 'Población'))
            ->add('provincia', 'entity', array('class' => 'SalesianosMainBundle:Provincia',
                                            'property' => 'nombre',
                                            'label' => 'Provincia'))
            ->add('titulacion', 'text', array('label' => 'Titulación', 
                                              'required' => false))
            ->add('experiencia', 'text', array('label' => 'Experiencia',
                                                'required' => false))
            ->add('descripcion', 'textarea', array('label' => 'Descripción',
                                                   'attr' => array('class' => 'tinymce'),
                                                   'required' => false
                                                    ))
            ->add('contrato', 'text', array('label' => 'Tipo de contrato',
                                            'required' => false))
            ->add('salario', 'text', array('label' => 'Salario',
                                            'required' => false))
            ->add('jornada', 'text', array('label' => 'Jornada laboral',
                                           'required' => false))
            ->add('horario', 'text', array('label' => 'Horario', 
                                           'required' => false))
            ->add('Publicar', 'submit')
            ->getForm();
    }
 
    public function getName()
    {
        return 'form_nueva_oferta';
    }
}