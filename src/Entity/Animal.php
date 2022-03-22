<?php

namespace App\Entity;

use App\Repository\AnimalRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

/**
 * @ORM\Entity(repositoryClass=AnimalRepository::class)
 */
class Animal
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"search"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"association"})
     * @Groups({"list_animal"})
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50)
     * @Groups({"association"})
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $sexe;

    /**
     * @ORM\Column(type="date", nullable=true)
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $date_of_birth;

    /**
     * @ORM\Column(type="text")
     * @Groups({"association"})
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $description;

    /**
     * @ORM\Column(type="string", length=32)
     * @Groups({"association"})
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $status;

    /**
     * @ORM\ManyToOne(targetEntity=User::class, inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"list_animal"})
     * @Groups({"animal"})
     */
    private $user;

    /**
     * @ORM\ManyToOne(targetEntity=Species::class, inversedBy="animals")
     * @ORM\JoinColumn(nullable=false)
     * @Groups({"animal"})
     */
    private $species;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"list_animal"})
     * @Groups({"animal"})
     * @Groups({"search"})
     */
    private $picture;

    public function __construct()
    {

        $this->picture = "https://img.freepik.com/vecteurs-libre/adoptez-concept-pour-animaux-compagnie_52683-36945.jpg?t=st=1646213831~exp=1646214431~hmac=00d6b92e1575431cc14a08b3b3bb1fea2cec616be783a249b0bd9b76a212372e&w=740";

    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getSexe(): ?string
    {
        return $this->sexe;
    }

    public function setSexe(string $sexe): self
    {
        $this->sexe = $sexe;

        return $this;
    }

    public function getDateOfBirth(): ?\DateTimeInterface
    {
        return $this->date_of_birth;
    }

    public function setDateOfBirth(?\DateTimeInterface $date_of_birth): self
    {
        $this->date_of_birth = $date_of_birth;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getSpecies(): ?Species
    {
        return $this->species;
    }

    public function setSpecies(?Species $species): self
    {
        $this->species = $species;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }
}
