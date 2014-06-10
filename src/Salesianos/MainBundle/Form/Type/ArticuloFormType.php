<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
 
class ArticuloFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        $builder->add('titulo', 'text', array('label' => 'Título',
                                              'required' => true))
                ->add('file', null, array('label' => 'Imagen'))
                ->add('contenido', 'textarea', array('label' => 'Contenido',
                                                   'attr' => array('class' => 'tinymce'),
                                                   'required' => true
                                                    ))
                ->add('fecha_publi', 'date', array(
                                        'format' => 'dMy',
                                        'data' => new \DateTime('now'),
                                        'label' => 'Publicado'))
                ->add('Guardar', 'submit')
                ->getForm();
    }
 
    public function getName()
    {
        return 'form_articulo';
    }
}