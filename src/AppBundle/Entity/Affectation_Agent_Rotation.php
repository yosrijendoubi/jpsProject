<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Avance
 *
 * @ORM\Table(name="affectation", indexes={@ORM\Index(name="fk_agent", columns={"id_agent_rotation"}), @ORM\Index(name="fk_marche", columns={"id_marche"})})
 * @ORM\Entity(repositoryClass="AppBundle\Repository\AvanceRepository")
 */
class Affectation_Agent_Rotation
{
    /**
     * @var int
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var \Employe
     *
     * @ORM\ManyToOne(targetEntity="Employe")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_agent_rotation", referencedColumnName="id_emp")
     * })
     */
    private $idAgent;

    /**
     * @var \Marche
     *
     * @ORM\ManyToOne(targetEntity="Marche")
     * @ORM\JoinColumns({
     *   @ORM\JoinColumn(name="id_marche", referencedColumnName="id_marche")
     * })
     */
    private $idMarche;


    /**
     * @var \DateTime
     *
     * @ORM\Column(name="date", type="date")
     */
    private $date;

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType($type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getRole()
    {
        return $this->role;
    }

    /**
     * @param string $role
     */
    public function setRole($role)
    {
        $this->role = $role;
    }


    /**
     * @var string
     *
     * @ORM\Column(name="type", type="string", length=10, nullable=false)
     */
    private $type;


    /**
     * @var string
     *
     * @ORM\Column(name="role", type="string", length=20, nullable=false)
     */
    private $role;


    /**
     * Get id
     *
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return \Employe
     */
    public function getIdAgent()
    {
        return $this->idAgent;
    }

    /**
     * @param \Employe $idAgent
     */
    public function setIdAgent($idAgent)
    {
        $this->idAgent = $idAgent;
    }


    /**
     * Set idMarche
     *
     * @param integer $idMarche
     *
     * @return Avance
     */
    public function setIdMarche($idMarche)
    {
        $this->idMarche = $idMarche;

        return $this;
    }

    /**
     * Get idMarche
     *
     * @return int
     */
    public function getIdMarche()
    {
        return $this->idMarche;
    }

    /**
     * Set date
     *
     * @param \DateTime $date
     *
     * @return Avance
     */
    public function setDate($date)
    {
        $this->date = $date;

        return $this;
    }

    /**
     * Get date
     *
     * @return \DateTime
     */
    public function getDate()
    {
        return $this->date;
    }
}

