<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="poblaciones")
 * @ORM\Entity(repositoryClass="Salesianos\MainBundle\Entity\PoblacionRepository")
 */
class Poblacion
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\ManyToOne(targetEntity="Provincia", inversedBy="poblaciones"))
     * 
     **/
    protected $provincia;

    /**
     * @ORM\Column(type="string")
     *
     **/
    protected $nombre;    

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
     * @return Poblacion
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
     * Set provincia
     *
     * @param \Salesianos\MainBundle\Entity\Provincia $provincia
     * @return Poblacion
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
}
