<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="candidatos")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\CandidatoRepository")
 */
class Candidato
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Usuario", cascade={"remove"})
     * @ORM\JoinColumn(name="usuario_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $usuario;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     */
    protected $nombre;
 
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * 
     */
    protected $apellidos;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_nac;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_reg;
 
    /**
     * @ORM\Column(type="string", length=15, nullable=true)
     */
    protected $telefono;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $email;

    /**
     * @ORM\Column(type="string", length=10, nullable=true)
     */
    protected $sexo;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected $codigo_postal;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $poblacion;
    
    /**
     * @ORM\ManyToOne(targetEntity="Provincia")
     * @ORM\JoinColumn(name="provincia_id", referencedColumnName="id")
     */ 
    protected $provincia;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $facebook;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $twitter;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $blog;

    /**
    * @ORM\ManyToMany(targetEntity="Oferta", inversedBy="candidatos")
    * @ORM\JoinTable(name="candidatos_ofertas")
    **/
    protected $ofertas;

    /**
     * @ORM\OneToOne(targetEntity="Curriculum", cascade={"remove"})
     * @ORM\JoinColumn(name="curriculum_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $curriculum;

    public function __construct() {
        $this->ofertas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->fecha_reg = new \DateTime('now');
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
     * @return Candidato
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
     * Set apellidos
     *
     * @param string $apellidos
     * @return Candidato
     */
    public function setApellidos($apellidos)
    {
        $this->apellidos = $apellidos;

        return $this;
    }

    /**
     * Get apellidos
     *
     * @return string 
     */
    public function getApellidos()
    {
        return $this->apellidos;
    }

    /**
     * Set fecha_nac
     *
     * @param string $fechaNac
     * @return Candidato
     */
    public function setFechaNac($fechaNac)
    {
        $this->fecha_nac = $fechaNac;

        return $this;
    }

    /**
     * Get fecha_nac
     *
     * @return string 
     */
    public function getFechaNac()
    {
        return $this->fecha_nac;
    }

    /**
     * Set telefono
     *
     * @param array $telefono
     * @return Candidato
     */
    public function setTelefono($telefono)
    {
        $this->telefono = $telefono;

        return $this;
    }

    /**
     * Get telefono
     *
     * @return array 
     */
    public function getTelefono()
    {
        return $this->telefono;
    }

    /**
     * Set usuario
     *
     * @param \Salesianos\MainBundle\Entity\Usuario $usuario
     * @return Candidato
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
     * @return Candidato
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
     * Has ofertas
     *
     * @return boolean
     */
    public function hasOferta(\Salesianos\MainBundle\Entity\Oferta $oferta)
    {
        for($aux=0; $aux < count($this->ofertas); $aux++){
            if($oferta == $this->ofertas[$aux]){
                return true;
            }
        }
        return false;
    }

    /**
     * Set email
     *
     * @param string $email
     * @return Candidato
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
     * Set codigo_postal
     *
     * @param integer $codigoPostal
     * @return Candidato
     */
    public function setCodigoPostal($codigoPostal)
    {
        $this->codigo_postal = $codigoPostal;

        return $this;
    }

    /**
     * Get codigo_postal
     *
     * @return integer 
     */
    public function getCodigoPostal()
    {
        return $this->codigo_postal;
    }

    /**
     * Set provincia
     *
     * @param \Salesianos\MainBundle\Entity\Provincia $provincia
     * @return Candidato
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
     * Set facebook
     *
     * @param string $facebook
     * @return Candidato
     */
    public function setFacebook($facebook)
    {
        $this->facebook = $facebook;

        return $this;
    }

    /**
     * Get facebook
     *
     * @return string 
     */
    public function getFacebook()
    {
        return $this->facebook;
    }

    /**
     * Set twitter
     *
     * @param string $twitter
     * @return Candidato
     */
    public function setTwitter($twitter)
    {
        $this->twitter = $twitter;

        return $this;
    }

    /**
     * Get twitter
     *
     * @return string 
     */
    public function getTwitter()
    {
        return $this->twitter;
    }

    /**
     * Set blog
     *
     * @param string $blog
     * @return Candidato
     */
    public function setBlog($blog)
    {
        $this->blog = $blog;

        return $this;
    }

    /**
     * Get blog
     *
     * @return string 
     */
    public function getBlog()
    {
        return $this->blog;
    }

    /**
     * Set curriculum
     *
     * @param \Salesianos\MainBundle\Entity\Curriculum $curriculum
     * @return Candidato
     */
    public function setCurriculum(\Salesianos\MainBundle\Entity\Curriculum $curriculum = null)
    {
        $this->curriculum = $curriculum;

        return $this;
    }

    /**
     * Get curriculum
     *
     * @return \Salesianos\MainBundle\Entity\Curriculum 
     */
    public function getCurriculum()
    {
        return $this->curriculum;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     * @return Candidato
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
     * Set sexo
     *
     * @param string $sexo
     * @return Candidato
     */
    public function setSexo($sexo)
    {
        $this->sexo = $sexo;

        return $this;
    }

    /**
     * Get sexo
     *
     * @return string 
     */
    public function getSexo()
    {
        return $this->sexo;
    }

    /**
     * Get edad
     *
     * @return int
     */
    public function getEdad()
    {
        $edad = 0;
        if($this->fecha_nac != null){
            $date = explode("/", $this->fecha_nac->format('d/m/Y'));
            $edad = (date("dm", date("U", mktime(0, 0, 0, $date[1], $date[0], $date[2]))) > date("dm")
                 ? ((date("Y") - $date[2]) - 1)
                 : (date("Y") - $date[2]));
        }        
        return $edad;
    }


    /**
     * Set fecha_reg
     *
     * @param \DateTime $fechaReg
     * @return Candidato
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
