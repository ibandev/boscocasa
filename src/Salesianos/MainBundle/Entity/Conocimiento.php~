<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="conocimientos")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\EmpresaRepository")
 */
class Conocimiento
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="conocimientos", cascade={"remove"})
     * 
     **/
    protected $curriculum;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $conocimiento;

    /**
     * @ORM\Column(type="string", length=5)
     */
    protected $nivel;
 
    

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
     * Set conocimiento
     *
     * @param string $conocimiento
     * @return Conocimiento
     */
    public function setConocimiento($conocimiento)
    {
        $this->conocimiento = $conocimiento;

        return $this;
    }

    /**
     * Get conocimiento
     *
     * @return string 
     */
    public function getConocimiento()
    {
        return $this->conocimiento;
    }

    /**
     * Set nivel
     *
     * @param string $nivel
     * @return Conocimiento
     */
    public function setNivel($nivel)
    {
        $this->nivel = $nivel;

        return $this;
    }

    /**
     * Get nivel
     *
     * @return string 
     */
    public function getNivel()
    {
        return $this->nivel;
    }

    /**
     * Set curriculum
     *
     * @param \Salesianos\MainBundle\Entity\Curriculum $curriculum
     * @return Conocimiento
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
