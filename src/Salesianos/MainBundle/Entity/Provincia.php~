<?php
namespace Salesianos\MainBundle\Entity;
 
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity
 * @ORM\Table(name="provincias")
 */
class Provincia
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @ORM\Column(type="string")
     *
     **/
    protected $nombre;

    /**
     * @ORM\OneToMany(targetEntity="Empresa", mappedBy="provincia"))
     * 
     **/
    protected $empresas;
    
    /**
     * Constructor
     */
    public function __construct()
    {
        $this->poblaciones = new \Doctrine\Common\Collections\ArrayCollection();
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
     * @param integer $nombre
     * @return Provincia
     */
    public function setNombre($nombre)
    {
        $this->nombre = $nombre;

        return $this;
    }

    /**
     * Get nombre
     *
     * @return integer 
     */
    public function getNombre()
    {
        return $this->nombre;
    }

    /**
     * Add poblaciones
     *
     * @param \Salesianos\MainBundle\Entity\Poblacion $poblaciones
     * @return Provincia
     */
    public function addPoblacion(\Salesianos\MainBundle\Entity\Poblacion $poblaciones)
    {
        $this->poblaciones[] = $poblaciones;

        return $this;
    }

    /**
     * Remove poblaciones
     *
     * @param \Salesianos\MainBundle\Entity\Poblacion $poblaciones
     */
    public function removePoblacion(\Salesianos\MainBundle\Entity\Poblacion $poblaciones)
    {
        $this->poblaciones->removeElement($poblaciones);
    }

    /**
     * Get poblaciones
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getPoblaciones()
    {
        return $this->poblaciones;
    }

    /**
     * Add poblaciones
     *
     * @param \Salesianos\MainBundle\Entity\Poblacion $poblaciones
     * @return Provincia
     */
    public function addPoblacione(\Salesianos\MainBundle\Entity\Poblacion $poblaciones)
    {
        $this->poblaciones[] = $poblaciones;

        return $this;
    }

    /**
     * Remove poblaciones
     *
     * @param \Salesianos\MainBundle\Entity\Poblacion $poblaciones
     */
    public function removePoblacione(\Salesianos\MainBundle\Entity\Poblacion $poblaciones)
    {
        $this->poblaciones->removeElement($poblaciones);
    }

    /**
     * Add ofertas
     *
     * @param \Salesianos\MainBundle\Entity\Oferta $ofertas
     * @return Provincia
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
     * Add empresas
     *
     * @param \Salesianos\MainBundle\Entity\Empresa $empresas
     * @return Provincia
     */
    public function addEmpresa(\Salesianos\MainBundle\Entity\Empresa $empresas)
    {
        $this->empresas[] = $empresas;

        return $this;
    }

    /**
     * Remove empresas
     *
     * @param \Salesianos\MainBundle\Entity\Empresa $empresas
     */
    public function removeEmpresa(\Salesianos\MainBundle\Entity\Empresa $empresas)
    {
        $this->empresas->removeElement($empresas);
    }

    /**
     * Get empresas
     *
     * @return \Doctrine\Common\Collections\Collection 
     */
    public function getEmpresas()
    {
        return $this->empresas;
    }
}
