<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class EmpresaFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('nombre', 'text', array('label' => 'Nombre'))
                ->add('sector', 'entity', array('class' => 'SalesianosMainBundle:Sector',
                                                'property' => 'nombre',
                                                'label' => 'Sector'))
                ->add('file', null, array('label' => 'Logo'))
                ->add('email', 'email')
                ->add('url', 'text')
                ->add('poblacion', 'text', array('label' => 'Población'))          
                ->add('provincia', 'entity', array('class' => 'SalesianosMainBundle:Provincia',
                                            'property' => 'nombre',
                                            'label' => 'Provincia'))
                ->add('telefono', 'text', array('label' => 'Teléfono'))
                ->add('descripcion', 'textarea', array('label' => 'Descripción',
                                                        'attr' => array('class' => 'tinymce')                                                                       
                                                            ))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_datos_empresa';
    }

}