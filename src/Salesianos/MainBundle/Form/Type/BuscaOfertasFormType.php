<?php
namespace Salesianos\MainBundle\Form\Type;
 
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Doctrine\ORM\EntityRepository;
 
class BuscaOfertasFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('sector', 'entity', array('class' => 'SalesianosMainBundle:Sector',
                                            'property' => 'nombre',
                                            'label' => 'Sector',
                                            'empty_value' => 'Todos',
                                            'empty_data' => null,
                                            'required' => false))
            ->add('provincia', 'entity', array('class' => 'SalesianosMainBundle:Provincia',
                                            'property' => 'nombre',
                                            'label' => 'Provincia',
                                            'empty_value' => 'Todas',
                                            'empty_data' => null,
                                            'required' => false,
                                            'query_builder' => function(EntityRepository $repository) {
                                                    return $repository->createQueryBuilder('p')
                                                        ->orderBy('p.nombre', 'ASC');
                                                }))
            ->add('Buscar', 'submit')
            ->getForm();
    }
 
    public function getName()
    {
        return 'form_busca_ofertas';
    }
}