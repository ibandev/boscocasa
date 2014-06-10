<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="articulos")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\ArticuloRepository")
 */
class Articulo
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     *
     */
    protected $titulo;

    /**
     * @ORM\Column(type="text", nullable=true)
     * 
     */
    protected $contenido;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_publi;

    public function upload()
    {        
        if (null === $this->getFile()) {
            return;
        }
        $this->path = 'art_'.$this->getId().'.'.$this->file->guessExtension();
        $this->getFile()->move(
            $this->getUploadRootDir(),
            $this->path
        );
        $this->file = null;
    }

    /**
     * Sets file.
     *
     * @param UploadedFile $file
     */
    public function setFile(UploadedFile $file = null)
    {
        $this->file = $file;
    }

    /**
     * Get file.
     *
     * @return UploadedFile
     */
    public function getFile()
    {
        return $this->file;
    }

    public function getAbsolutePath()
    {
        return null === $this->path
            ? null
            : $this->getUploadRootDir().'/'.$this->path;
    }

    public function getWebPath()
    {
        return null === $this->path
            ? null
            : $this->getUploadDir().'/'.$this->path;
    }

    protected function getUploadRootDir()
    {
        // the absolute directory path where uploaded
        // documents should be saved
        return __DIR__.'/../../../../web/'.$this->getUploadDir();
    }

    protected function getUploadDir()
    {
        // get rid of the __DIR__ so it doesn't screw up
        // when displaying uploaded doc/image in the view.
        return 'images/blog';
    }

    /**
     * Constructor
     */
    public function __construct()
    {

    }


    /**
     * Get previa
     *
     * @return string 
     */
    public function getPrevia($num)
    {
        return substr($this->contenido, 0, $num)."...";
    }

    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set titulo
     *
     * @param string $titulo
     * @return Articulo
     */
    public function setTitulo($titulo)
    {
        $this->titulo = $titulo;

        return $this;
    }

    /**
     * Get titulo
     *
     * @return string 
     */
    public function getTitulo()
    {
        return $this->titulo;
    }

    /**
     * Set contenido
     *
     * @param string $contenido
     * @return Articulo
     */
    public function setContenido($contenido)
    {
        $this->contenido = $contenido;

        return $this;
    }

    /**
     * Get contenido
     *
     * @return string 
     */
    public function getContenido()
    {
        return $this->contenido;
    }

    

    /**
     * Set path
     *
     * @param string $path
     * @return Articulo
     */
    public function setPath($path)
    {
        $this->path = $path;

        return $this;
    }

    /**
     * Get path
     *
     * @return string 
     */
    public function getPath()
    {
        return $this->path;
    }

    /**
     * Set fecha_publi
     *
     * @param \DateTime $fechaPubli
     * @return Articulo
     */
    public function setFechaPubli($fechaPubli)
    {
        $this->fecha_publi = $fechaPubli;

        return $this;
    }

    /**
     * Get fecha_publi
     *
     * @return \DateTime 
     */
    public function getFechaPubli()
    {
        return $this->fecha_publi;
    }
}
