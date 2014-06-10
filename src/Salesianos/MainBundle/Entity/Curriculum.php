<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="curriculum")
 * 
 */
class Curriculum
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\OneToOne(targetEntity="Candidato")
     * @ORM\JoinColumn(name="candidato_id", referencedColumnName="id", onDelete="CASCADE")
     **/
    protected $candidato;

    /**
     *  @ORM\OneToMany(targetEntity="Estudio", mappedBy="curriculum", cascade={"remove"})
     */
    protected $estudios;
 
    /**
     *  @ORM\OneToMany(targetEntity="Experiencia", mappedBy="curriculum", cascade={"remove"})
     */
    protected $experiencias;

    /**
     *  @ORM\OneToMany(targetEntity="Conocimiento", mappedBy="curriculum", cascade={"remove"})
     */
    protected $conocimientos;

    /**
     *  @ORM\OneToMany(targetEntity="Idioma", mappedBy="curriculum", cascade={"remove"})
     */
    protected $idiomas;

    /**
     *  @ORM\Column(type="boolean", nullable=true)
     */
    protected $carnet;

    /**
     *  @ORM\Column(type="boolean", nullable=true)
     */
    protected $vehiculo;

    /**
     *  @ORM\Column(type="boolean", nullable=true)
     */
    protected $movilidad;

    /**
     *  @ORM\Column(type="date", nullable=true)
     */
    protected $ultima_actualizacion;


    /**
     * Constructor
     */
    public function __construct()
    {
        $this->estudios = new \Doctrine\Common\Collections\ArrayCollection();
        $this->experiencias = new \Doctrine\Common\Collections\ArrayCollection();
        $this->conocimientos = new \Doctrine\Common\Collections\ArrayCollection();
        $this->idiomas = new \Doctrine\Common\Collections\ArrayCollection();
        $this->carnet = false;
        $this->vehiculo = false;
        $this->movilidad = false;
        $this->ultima_actualizacion = new \DateTime("now");
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
     * Set candidato
     *
     * @param \Salesianos\MainBundle\Entity\Candidato $candidato
     * @return Curriculum
     */
    public function setCandidato(\Salesianos\MainBundle\Entity\Candidato $candidato = null)
    {
        $this->candidato = $candidato;

        return $this;
    }

    /**
     * Get candidato
     *
     * @return \Salesianos\MainBundle\Entity\Candidato 
     */
    public function getCandidato()
    {
        return $this->candidato;
    }

    /**
     * Add estudios
     *
     * @param \Salesianos\MainBundle\Entity\Estudio $estudios
     * @return Curriculum
     */
    public function addEstudio(\Salesianos\MainBundle\Entity\Estudio $estudios)
    {
        $this->estudios[] = $estudios;

        return $this;
    }

    /**
     * Remove estudios
     *
     * @param \Salesianos\MainBundle\Entity\Estudio $estudios
     */
    public function removeEstudio(\Salesianos\MainBundle\Entity\Estudio $estudios)
    {
        $this->estudios->removeElement($estudios);
    }

    /**
     * Get estudios
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEstudios()
    {
        return $this->estudios;
    }

    /**
     * Add experiencias
     *
     * @param \Salesianos\MainBundle\Entity\Experiencia $experiencias
     * @return Curriculum
     */
    public function addExperiencia(\Salesianos\MainBundle\Entity\Experiencia $experiencias)
    {
        $this->experiencias[] = $experiencias;

        return $this;
    }

    /**
     * Remove experiencias
     *
     * @param \Salesianos\MainBundle\Entity\Experiencia $experiencias
     */
    public function removeExperiencia(\Salesianos\MainBundle\Entity\Experiencia $experiencias)
    {
        $this->experiencias->removeElement($experiencias);
    }

    /**
     * Get experiencias
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getExperiencias()
    {
        return $this->experiencias;
    }

    /**
     * Add conocimientos
     *
     * @param \Salesianos\MainBundle\Entity\Conocimiento $conocimientos
     * @return Curriculum
     */
    public function addConocimiento(\Salesianos\MainBundle\Entity\Conocimiento $conocimientos)
    {
        $this->conocimientos[] = $conocimientos;

        return $this;
    }

    /**
     * Remove conocimientos
     *
     * @param \Salesianos\MainBundle\Entity\Conocimiento $conocimientos
     */
    public function removeConocimiento(\Salesianos\MainBundle\Entity\Conocimiento $conocimientos)
    {
        $this->conocimientos->removeElement($conocimientos);
    }

    /**
     * Get conocimientos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getConocimientos()
    {
        return $this->conocimientos;
    }

    /**
     * Add idiomas
     *
     * @param \Salesianos\MainBundle\Entity\Idioma $idiomas
     * @return Curriculum
     */
    public function addIdioma(\Salesianos\MainBundle\Entity\Idioma $idiomas)
    {
        $this->idiomas[] = $idiomas;

        return $this;
    }

    /**
     * Remove idiomas
     *
     * @param \Salesianos\MainBundle\Entity\Idioma $idiomas
     */
    public function removeIdioma(\Salesianos\MainBundle\Entity\Idioma $idiomas)
    {
        $this->idiomas->removeElement($idiomas);
    }

    /**
     * Get idiomas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getIdiomas()
    {
        return $this->idiomas;
    }

    /**
     * Set permiso
     *
     * @param boolean $permiso
     * @return Curriculum
     */
    public function setPermiso($permiso)
    {
        $this->permiso = $permiso;

        return $this;
    }

    /**
     * Get permiso
     *
     * @return boolean 
     */
    public function getPermiso()
    {
        return $this->permiso;
    }

    /**
     * Set vehiculo
     *
     * @param boolean $vehiculo
     * @return Curriculum
     */
    public function setVehiculo($vehiculo)
    {
        $this->vehiculo = $vehiculo;

        return $this;
    }

    /**
     * Get vehiculo
     *
     * @return boolean 
     */
    public function getVehiculo()
    {
        return $this->vehiculo;
    }

    /**
     * Set ultima_actualizacion
     *
     * @param \DateTime $ultimaActualizacion
     * @return Curriculum
     */
    public function setUltimaActualizacion($ultimaActualizacion)
    {
        $this->ultima_actualizacion = $ultimaActualizacion;

        return $this;
    }

    /**
     * Get ultima_actualizacion
     *
     * @return \DateTime 
     */
    public function getUltimaActualizacion()
    {
        return $this->ultima_actualizacion;
    }

    /**
     * Set carnet
     *
     * @param boolean $carnet
     * @return Curriculum
     */
    public function setCarnet($carnet)
    {
        $this->carnet = $carnet;

        return $this;
    }

    /**
     * Get carnet
     *
     * @return boolean 
     */
    public function getCarnet()
    {
        return $this->carnet;
    }

    /**
     * Set movilidad
     *
     * @param boolean $movilidad
     * @return Curriculum
     */
    public function setMovilidad($movilidad)
    {
        $this->movilidad = $movilidad;

        return $this;
    }

    /**
     * Get movilidad
     *
     * @return boolean 
     */
    public function getMovilidad()
    {
        return $this->movilidad;
    }
    
}
