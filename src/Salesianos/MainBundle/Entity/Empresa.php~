<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\HttpFoundation\File\UploadedFile;

/**
 * @ORM\Entity
 * @ORM\Table(name="empresas")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\EmpresaRepository")
 */
class Empresa
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Usuario")
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id")
     **/
    protected $usuario;

    /**
    * @ORM\ManyToOne(targetEntity="Sector")
    * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
    **/
    protected $sector;
 
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     */
    protected $nombre;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $url;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $poblacion;

    /**
     * @ORM\ManyToOne(targetEntity="Provincia", inversedBy="empresas")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id")
     */ 
    protected $provincia;
 
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $telefono;
 
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;

    /**
     *  @ORM\OneToMany(targetEntity="Oferta", mappedBy="empresa")
     */
    protected $ofertas;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $path;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $valida;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_reg;

    /**
     * @Assert\File(maxSize="6000000")
     */
    private $file;

    public function upload()
    {        
        if (null === $this->getFile()) {
            return;
        }
        $this->path = 'logo_emp_'.$this->getId().'.'.$this->file->guessExtension();
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
        return 'uploads/empresas/logos';
    }

    /**
     * Constructor
     */
    public function __construct()
    {
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha_reg = new \DateTime('now');
        $this->path = 'default.png';
        $this->valid = false;
    }

    /**
     * Get previa
     *
     * @return string 
     */
    public function getPrevia()
    {
        return substr($this->descripcion, 0, 100)."...";
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
     * Set nombre
     *
     * @param string $nombre
     * @return Empresa
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return string 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Empresa
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string 
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set url
     *
     * @param string $url
     * @return Empresa
     */
    public function setUrl($url)
    {
        $this->url = $url;

        return $this;
    }

    /**
     * Get url
     *
     * @return string 
     */
    public function getUrl()
    {
        return $this->url;
    }

    /**
     * Set telefono
     *
     * @param string $telefono
     * @return Empresa
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return string 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Empresa
     */
    public function setDescripcion($descripcion)
    {
        $this->descripcion = $descripcion;

        return $this;
    }

    /**
     * Get descripcion
     *
     * @return string 
     */
    public function getDescripcion()
    {
        return $this->descripcion;
    }

    /**
     * Set usuario
     *
     * @param \Salesianos\MainBundle\Entity\Usuario $usuario
     * @return Empresa
     */
    public function setUsuario(\Salesianos\MainBundle\Entity\Usuario $usuario = null)
    {
        $this->usuario = $usuario;

        return $this;
    }

    /**
     * Get usuario
     *
     * @return \Salesianos\MainBundle\Entity\Usuario 
     */
    public function getUsuario()
    {
        return $this->usuario;
    }

    /**
     * Add ofertas
     *
     * @param \Salesianos\MainBundle\Entity\Oferta $ofertas
     * @return Empresa
     */
    public function addOferta(\Salesianos\MainBundle\Entity\Oferta $ofertas)
    {
        $this->ofertas[] = $ofertas;

        return $this;
    }

    /**
     * Remove ofertas
     *
     * @param \Salesianos\MainBundle\Entity\Oferta $ofertas
     */
    public function removeOferta(\Salesianos\MainBundle\Entity\Oferta $ofertas)
    {
        $this->ofertas->removeElement($ofertas);
    }

    /**
     * Get ofertas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getOfertas()
    {
        return $this->ofertas;
    }

    /**
     * Set sector
     *
     * @param \Salesianos\MainBundle\Entity\Sector $sector
     * @return Empresa
     */
    public function setSector(\Salesianos\MainBundle\Entity\Sector $sector = null)
    {
        $this->sector = $sector;

        return $this;
    }

    /**
     * Get sector
     *
     * @return \Salesianos\MainBundle\Entity\Sector 
     */
    public function getSector()
    {
        return $this->sector;
    }

    /**
     * Set path
     *
     * @param string $path
     * @return Empresa
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
     * Set poblacion
     *
     * @param string $poblacion
     * @return Empresa
     */
    public function setPoblacion($poblacion)
    {
        $this->poblacion = $poblacion;

        return $this;
    }

    /**
     * Get poblacion
     *
     * @return string 
     */
    public function getPoblacion()
    {
        return $this->poblacion;
    }

    /**
     * Set provincia
     *
     * @param \Salesianos\MainBundle\Entity\Provincia $provincia
     * @return Empresa
     */
    public function setProvincia(\Salesianos\MainBundle\Entity\Provincia $provincia = null)
    {
        $this->provincia = $provincia;

        return $this;
    }

    /**
     * Get provincia
     *
     * @return \Salesianos\MainBundle\Entity\Provincia 
     */
    public function getProvincia()
    {
        return $this->provincia;
    }

    /**
     * Set valid
     *
     * @param boolean $valid
     * @return Empresa
     */
    public function setValid($valid)
    {
        $this->valid = $valid;
        return $this;
    }

    /**
     * Get valid
     *
     * @return boolean 
     */
    public function getValid()
    {
        return $this->valid;
    }

    /**
     * Set valida
     *
     * @param boolean $valida
     * @return Empresa
     */
    public function setValida($valida)
    {
        $this->valida = $valida;

        return $this;
    }

    /**
     * Get valida
     *
     * @return boolean 
     */
    public function getValida()
    {
        return $this->valida;
    }

    /**
     * Set fecha_reg
     *
     * @param \DateTime $fechaReg
     * @return Empresa
     */
    public function setFechaReg($fechaReg)
    {
        $this->fecha_reg = $fechaReg;

        return $this;
    }

    /**
     * Get fecha_reg
     *
     * @return \DateTime 
     */
    public function getFechaReg()
    {
        return $this->fecha_reg;
    }
}
