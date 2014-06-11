<?php

namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="ofertas")
 */
class Oferta
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Empresa", inversedBy="ofertas")
     * 
     **/
    protected $empresa;
 
    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $puesto;

    /**
     *@ORM\ManyToOne(targetEntity="Sector")
     * @ORM\JoinColumn(name="sector_id", referencedColumnName="id")
     */
    protected $sector;

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
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_ini;
 
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_fin;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $titulacion;

    /**
     * @ORM\Column(type="string", nullable=true)
     */
    protected $experiencia;
 
    /**
     * @ORM\Column(type="text", nullable=true)
     */
    protected $descripcion;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $contrato;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $salario;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $jornada;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     */
    protected $horario;

    /**
    * @ORM\ManyToMany(targetEntity="Candidato", mappedBy="ofertas")
    **/
    private $candidatos;

    /**
     * @ORM\Column(type="boolean", nullable=true)
     */
    protected $visible;

    public function __construct()
    {
        $this->fecha_ini = new \DateTime("now");
        $this->visible = true;
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
     * Set puesto
     *
     * @param string $puesto
     * @return Oferta
     */
    public function setPuesto($puesto)
    {
        $this->puesto = $puesto;

        return $this;
    }

    /**
     * Get puesto
     *
     * @return string 
     */
    public function getPuesto()
    {
        return $this->puesto;
    }

    /**
     * Set poblacion
     *
     * @param string $poblacion
     * @return Oferta
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
     * Set fecha_ini
     *
     * @param \DateTime $fechaIni
     * @return Oferta
     */
    public function setFechaIni($fechaIni)
    {
        $this->fecha_ini = $fechaIni;

        return $this;
    }

    /**
     * Get fecha_ini
     *
     * @return \DateTime 
     */
    public function getFechaIni()
    {
        return $this->fecha_ini;
    }

    /**
     * Set fecha_fin
     *
     * @param \DateTime $fechaFin
     * @return Oferta
     */
    public function setFechaFin($fechaFin)
    {
        $this->fecha_fin = $fechaFin;

        return $this;
    }

    /**
     * Get fecha_fin
     *
     * @return \DateTime 
     */
    public function getFechaFin()
    {
        return $this->fecha_fin;
    }

    /**
     * Set descripcion
     *
     * @param string $descripcion
     * @return Oferta
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
     * Set contrato
     *
     * @param string $contrato
     * @return Oferta
     */
    public function setContrato($contrato)
    {
        $this->contrato = $contrato;

        return $this;
    }

    /**
     * Get contrato
     *
     * @return string 
     */
    public function getContrato()
    {
        return $this->contrato;
    }

    /**
     * Set jornada
     *
     * @param string $jornada
     * @return Oferta
     */
    public function setJornada($jornada)
    {
        $this->jornada = $jornada;

        return $this;
    }

    /**
     * Get jornada
     *
     * @return string 
     */
    public function getJornada()
    {
        return $this->jornada;
    }

    /**
     * Set horario
     *
     * @param string $horario
     * @return Oferta
     */
    public function setHorario($horario)
    {
        $this->horario = $horario;

        return $this;
    }

    /**
     * Get horario
     *
     * @return string 
     */
    public function getHorario()
    {
        return $this->horario;
    }

    /**
     * Set empresa
     *
     * @param \Salesianos\MainBundle\Entity\Empresa $empresa
     * @return Oferta
     */
    public function setEmpresa(\Salesianos\MainBundle\Entity\Empresa $empresa = null)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return \Salesianos\MainBundle\Entity\Empresa 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set sector
     *
     * @param \Salesianos\MainBundle\Entity\Sector $sector
     * @return Oferta
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
     * Set provincia
     *
     * @param \Salesianos\MainBundle\Entity\Provincia $provincia
     * @return Oferta
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
     * Add candidatos
     *
     * @param \Salesianos\MainBundle\Entity\Candidato $candidatos
     * @return Oferta
     */
    public function addCandidato(\Salesianos\MainBundle\Entity\Candidato $candidatos)
    {
        $this->candidatos[] = $candidatos;

        return $this;
    }

    /**
     * Remove candidatos
     *
     * @param \Salesianos\MainBundle\Entity\Candidato $candidatos
     */
    public function removeCandidato(\Salesianos\MainBundle\Entity\Candidato $candidatos)
    {
        $this->candidatos->removeElement($candidatos);
    }

    /**
     * Get candidatos
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getCandidatos()
    {
        return $this->candidatos;
    }

    /**
     * Set titulacion
     *
     * @param string $titulacion
     * @return Oferta
     */
    public function setTitulacion($titulacion)
    {
        $this->titulacion = $titulacion;

        return $this;
    }

    /**
     * Get titulacion
     *
     * @return string 
     */
    public function getTitulacion()
    {
        return $this->titulacion;
    }

    /**
     * Set experiencia
     *
     * @param string $experiencia
     * @return Oferta
     */
    public function setExperiencia($experiencia)
    {
        $this->experiencia = $experiencia;

        return $this;
    }

    /**
     * Get experiencia
     *
     * @return string 
     */
    public function getExperiencia()
    {
        return $this->experiencia;
    }

    /**
     * Set salario
     *
     * @param string $salario
     * @return Oferta
     */
    public function setSalario($salario)
    {
        $this->salario = $salario;

        return $this;
    }

    /**
     * Get salario
     *
     * @return string 
     */
    public function getSalario()
    {
        return $this->salario;
    }

    public function hasFinished()
    {
        $fecha_actual = strtotime(date("d-m-Y"));
        $fecha_fin = strtotime($this->fecha_fin->format("d-m-Y"));
        if($fecha_actual > $fecha_fin){
            return true;
        }
        return false;
    }



    /**
     * Set visible
     *
     * @param boolean $visible
     * @return Oferta
     */
    public function setVisible($visible)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * Get visible
     *
     * @return boolean 
     */
    public function getVisible()
    {
        return $this->visible;
    }
}
