<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="estudios")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\EmpresaRepository")
 */
class Estudio
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="estudios", cascade={"remove"})
     * 
     **/
    protected $curriculum;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $titulo;

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
     * Set titulo
     *
     * @param string $titulo
     * @return Estudio
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
     * Set fecha_ini
     *
     * @param \DateTime $fechaIni
     * @return Estudio
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
     * @return Estudio
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
     * @return Estudio
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
