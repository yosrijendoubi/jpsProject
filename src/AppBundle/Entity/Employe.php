<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Vich\UploaderBundle\Entity\File as EmbeddedFile;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Employe
 *
 * @ORM\Table(name="employe")
 * @ORM\Entity
 * @Vich\Uploadable
 */
class Employe
{
    /**
     * @return int
     */
    public function getIdEmp()
    {
        return $this->idEmp;
    }

    /**
     * @param int $idEmp
     */
    public function setIdEmp($idEmp)
    {
        $this->idEmp = $idEmp;
    }

    /**
     * @return string
     */
    public function getNom()
    {
        return $this->nom;
    }

    /**
     * @param string $nom
     */
    public function setNom($nom)
    {
        $this->nom = $nom;
    }

    /**
     * @return string
     */
    public function getPrenom()
    {
        return $this->prenom;
    }

    /**
     * @param string $prenom
     */
    public function setPrenom($prenom)
    {
        $this->prenom = $prenom;
    }

    /**
     * @return string
     */
    public function getTel()
    {
        return $this->tel;
    }

    /**
     * @param string $tel
     */
    public function setTel($tel)
    {
        $this->tel = $tel;
    }

    /**
     * @var integer
     *
     * @ORM\Column(name="id_emp", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    private $idEmp;

    /**
     * @var string
     *
     * @ORM\Column(name="nom", type="string", length=50, nullable=false)
     */
    private $nom;

    /**
     * @var string
     *
     * @ORM\Column(name="prenom", type="string", length=50, nullable=false)
     */
    private $prenom;

    /**
     * @var string
     *
     * @ORM\Column(name="tel", type="string", length=12, nullable=false)
     */
    private $tel;




    /**
     * @return \Marche
     */
    public function getIdMarche()
    {
        return $this->idMarche;
    }

    /**
     * @param \Marche $idMarche
     */
    public function setIdMarche($idMarche)
    {
        $this->idMarche = $idMarche;
    }


    public function __toString()
    {
        return $this->nom;
    }

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
     * NOTE: This is not a mapped field of entity metadata, just a simple property.
     *
     * @Vich\UploadableField(mapping="employe", fileNameProperty="imageName")
     * @var File
     */
    private $imageFile;


    /**
     * @ORM\Column(type="string")
     *
     * @var string|null
     */
    private $imageName;

    /**
     * @ORM\Column(type="datetime")
     *
     * @var \DateTimeInterface|null
     */
    private $updatedAt;

    /**
     * @var string
     *
     * @ORM\Column(name="RIB", type="string", length=50, nullable=false)
     */
    private $RIB;

    /**
     * @var string
     *
     * @ORM\Column(name="NumCNSS", type="string", length=50, nullable=false)
     */
    private $NumCNSS;

    /**
     * @var TextareaType
     *
     * @ORM\Column(name="Vetements", type="string", length=200, nullable=false)
     */
    private $Vetements;




    /**
     * @return string|null
     */
    public function getImageName()
    {
        return $this->imageName;
    }

    /**
     * @param string|null $imageName
     */
    public function setImageName($imageName)
    {
        $this->imageName = $imageName;
    }

    /**
     * @return \DateTimeInterface|null
     */
    public function getUpdatedAt()
    {
        return $this->updatedAt;
    }

    /**
     * @param \DateTimeInterface|null $updatedAt
     */
    public function setUpdatedAt($updatedAt)
    {
        $this->updatedAt = $updatedAt;
    }
    public function setImageFile(File $imageFile = null)
    {
        $this->imageFile = $imageFile;

        if (null !== $imageFile) {
            // It is required that at least one field changes if you are using doctrine
            // otherwise the event listeners won't be called and the file is lost
            $this->updatedAt = new \DateTimeImmutable();
        }
    }
    /**
     * @return File|null
     */
    public function getImageFile()
    {
        return $this->imageFile;
    }

    /**
     * @return string
     */
    public function getRIB()
    {
        return $this->RIB;
    }

    /**
     * @param string $RIB
     */
    public function setRIB($RIB)
    {
        $this->RIB = $RIB;
    }

    /**
     * @return string
     */
    public function getNumCNSS()
    {
        return $this->NumCNSS;
    }

    /**
     * @param string $NumCNSS
     */
    public function setNumCNSS($NumCNSS)
    {
        $this->NumCNSS = $NumCNSS;
    }

    /**
     * @return TextareaType
     */
    public function getVetements()
    {
        return $this->Vetements;
    }

    /**
     * @param TextareaType $Vetements
     */
    public function setVetements($Vetements)
    {
        $this->Vetements = $Vetements;
    }






}

