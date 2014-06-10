<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="idiomas")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\EmpresaRepository")
 */
class Idioma
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Curriculum", inversedBy="idiomas", cascade={"remove"})
     * 
     **/
    protected $curriculum;

    /**
     * @ORM\Column(type="string", length=150)
     */
    protected $idioma;

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
     * Set idioma
     *
     * @param string $idioma
     * @return Idioma
     */
    public function setIdioma($idioma)
    {
        $this->idioma = $idioma;
        return $this;
    }

    /**
     * Get idioma
     *
     * @return string 
     */
    public function getIdioma()
    {
        return $this->idioma;
    }

    /**
     * Set nivel
     *
     * @param string $nivel
     * @return Idioma
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
     * @return Idioma
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
