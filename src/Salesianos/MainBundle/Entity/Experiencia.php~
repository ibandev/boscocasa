<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="experiencias")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\EmpresaRepository")
 */
class Experiencia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="experiencias", cascade={"remove"})
     * 
     **/
    protected $curriculum;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $puesto;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $empresa;

    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_ini;
 
    /**
     * @ORM\Column(type="date", nullable=true)
     */
    protected $fecha_fin;

    

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
     * @return Experiencia
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
     * Set empresa
     *
     * @param string $empresa
     * @return Experiencia
     */
    public function setEmpresa($empresa)
    {
        $this->empresa = $empresa;

        return $this;
    }

    /**
     * Get empresa
     *
     * @return string 
     */
    public function getEmpresa()
    {
        return $this->empresa;
    }

    /**
     * Set fecha_ini
     *
     * @param \DateTime $fechaIni
     * @return Experiencia
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
     * @return Experiencia
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
     * Set curriculum
     *
     * @param \Salesianos\MainBundle\Entity\Curriculum $curriculum
     * @return Experiencia
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
}
