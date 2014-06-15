<?php
namespace Salesianos\MainBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Validator\Constraints\Length;
use Salesianos\MainBundle\Entity\Oferta;
use Salesianos\MainBundle\Entity\Estudio;
use Salesianos\MainBundle\Entity\Curriculum;
use Salesianos\MainBundle\Entity\Logo;
use Salesianos\MainBundle\Entity\Experiencia;
use Salesianos\MainBundle\Entity\Idioma;
use Salesianos\MainBundle\Entity\Conocimiento;
use FOS\UserBundle\FOSUserEvents;
use FOS\UserBundle\Event\FormEvent;
use FOS\UserBundle\Event\FilterUserResponseEvent;
use FOS\UserBundle\Event\GetResponseUserEvent;
use FOS\UserBundle\Model\UserInterface;
use Symfony\Component\DependencyInjection\ContainerAware;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\Security\Core\Exception\AccessDeniedException;
use Salesianos\MainBundle\Form\Type\OfertaFormType;
use Salesianos\MainBundle\Form\Type\EmpresaFormType;
use Salesianos\MainBundle\Form\Type\BuscaOfertasFormType;
use Salesianos\MainBundle\Form\Type\CandidatoFormType;
use Salesianos\MainBundle\Form\Type\OtrosDatosFormType;
use Salesianos\MainBundle\Form\Type\LogoFormType;
use Salesianos\MainBundle\Form\Type\EstudioFormType;
use Salesianos\MainBundle\Form\Type\ConocimientoFormType;
use Salesianos\MainBundle\Form\Type\ExperienciaFormType;
use Salesianos\MainBundle\Form\Type\IdiomaFormType;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\Filesystem\Exception\IOExceptionInterface;

class EmpresaController extends Controller
{

    //Muestra los datos de la empresa y permite modificarlos
    public function datosempresaAction()
    {
        $request = $this->getRequest();

        $user = $this->container->get('security.context')->getToken()->getUser();         
        $empresa = $this->getDoctrine()->getRepository('SalesianosMainBundle:Empresa')->findByUser($user);

        $form = $this->createForm(new EmpresaFormType(), $empresa);

        if($request->getMethod() == 'POST'){

            $form->handleRequest($request);
            if($form->isValid()){
                $em = $this->getDoctrine()->getManager();
                $empresa->upload();

                $em->persist($empresa);
                $user->setEmail($empresa->getEmail());
                $em->persist($user);
                $em->flush();
            }
        }

        return $this->render('SalesianosMainBundle:Main:datos_empresa.html.twig', array(
                'form' => $form->createView(),
                'empresa' => $empresa
            ));
    }

    public function descargarCVAction($id_oferta, $id_candidato)
    {
        $fs = new Filesystem();
        $candidato = $this->getDoctrine()->getRepository('SalesianosMainBundle:Candidato')->find($id_candidato);
        $path = __DIR__.'/../../../../web/temp/cv_'.$this->quitar_tildes($candidato->getApellidos()).', '.$this->quitar_tildes($candidato->getNombre()).'.pdf';
        if($fs->exists($path)){
           $fs->remove($path);
        }
        $this->get('knp_snappy.pdf')->generateFromHtml(
            $this->renderView(
                'SalesianosMainBundle:Main:candidato.html.twig',
                array(
                    'candidato'  => $candidato
                )
            ), $path,
                array('encoding' => 'UTF-8', 
                      'minimum-font-size' => '20')
            );     
        $content = file_get_contents($path);

        $response = new Response();
        $response->headers->set('Content-Type', 'text/pdf');
        $response->headers->set('Content-Disposition', 'attachment;filename="cv_'.$candidato->getApellidos().', '.$candidato->getNombre().'.pdf"');
        $response->setContent($content);
        return $response;
    }

    public function descargartodosCVAction($id_oferta)
    {
        $fs = new Filesystem();
        $oferta = $this->getDoctrine()->getRepository('SalesianosMainBundle:Oferta')->find($id_oferta);
        $candidatos = $oferta->getCandidatos();
        if($candidatos == null){
            return $this->redirect($this->generateUrl('salesianos_main_verinscritos',array('oferta' => $oferta)));
        }
        $zip = new \ZipArchive();
        $zipPath =  __DIR__.'/../../../../web/temp/';
        $zipName =  date('dmY').'cvs-'.substr($oferta->getPuesto(), 0, 30).".zip";
        if($fs->exists($zipPath.$zipName)){
            $fs->remove($zipPath.$zipName); 
        }
        $zip->open($zipPath.$zipName,  \ZipArchive::CREATE);

        foreach ($candidatos as $candidato) {
            $path = 'candidatos/cvs_'.$id_oferta.'/cv_'.$this->quitar_tildes($candidato->getApellidos()).', '.$this->quitar_tildes($candidato->getNombre()).'.pdf';
            if($fs->exists($path)){
               $fs->remove($path); 
            }
            $this->get('knp_snappy.pdf')->generateFromHtml(
                $this->renderView(
                    'SalesianosMainBundle:Main:candidato.html.twig',
                    array(
                        'candidato'  => $candidato
                    )
                ), $path,
                    array('encoding' => 'UTF-8', 
                          'minimum-font-size' => '20')
                );
            $i=0;
            $zip->addFile($path);
        }
        $zip->close();

        $content = file_get_contents($zipPath.$zipName);
        
        $response = new Response();        
        $response->headers->set('Content-Type', 'application/zip');
        $response->headers->set('Content-Disposition', 'attachment;filename="'.$zipName.'"');
        $response->setContent($content);
        return $response;
    }

    public function quitar_tildes($cadena) 
    {
        $no_permitidas= array ("á","é","í","ó","ú","Á","É","Í","Ó","Ú","ñ","À","Ã","Ì","Ò","Ù","Ã™","Ã ","Ã¨","Ã¬","Ã²","Ã¹","ç","Ç","Ã¢","ê","Ã®","Ã´","Ã»","Ã‚","ÃŠ","ÃŽ","Ã”","Ã›","ü","Ã¶","Ã–","Ã¯","Ã¤","«","Ò","Ã","Ã„","Ã‹");
        $permitidas= array ("a","e","i","o","u","A","E","I","O","U","n","N","A","E","I","O","U","a","e","i","o","u","c","C","a","e","i","o","u","A","E","I","O","U","u","o","O","i","a","e","U","I","A","E");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }


}
