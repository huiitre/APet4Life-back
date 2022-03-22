<?php

namespace App\Entity;

use App\Repository\SpeciesRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=SpeciesRepository::class)
 */
class Species
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
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"search"})
     * @Groups({"user"})
     * @Groups({"list_species"})
     * @Groups({"animal"})
     * @Assert\NotBlank
     */
    private $name;


    /**
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="species")
     */
    private $animals;

    /**
     * @ORM\ManyToMany(targetEntity=User::class, mappedBy="species")
     */
    private $users;

    public function __construct()
    {
        $this->animals = new ArrayCollection();
        $this->users = new ArrayCollection();
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

    /**
     * @return Collection<int, Animal>
     */
    public function getAnimals(): Collection
    {
        return $this->animals;
    }

    public function addAnimal(Animal $animal): self
    {
        if (!$this->animals->contains($animal)) {
            $this->animals[] = $animal;
            $animal->setSpecies($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getSpecies() === $this) {
                $animal->setSpecies(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->addSpecies($this);
        }

        return $this;
    }

    public function removeUser(User $user): self
    {
        if ($this->users->removeElement($user)) {
            $user->removeSpecies($this);
        }

        return $this;
    }
}
