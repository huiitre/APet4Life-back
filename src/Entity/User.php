<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use ZipCodeValidator\Constraints\ZipCode;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;

/**
 * @ORM\Entity(repositoryClass=UserRepository::class)
 */
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"search"})
     * @Groups({"user"})
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=25)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     */
    private $type;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     * @Groups({"list_animal"})
     * @Groups({"animal"})
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"user"})
     */
    private $firstname;

    /**
     * @ORM\Column(type="string", length=50, nullable=true)
     * @Groups({"user"})
     */
    private $lastname;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $siret;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"animal"})
     * @Groups({"search"})
     * @Assert\NotBlank
     */
    private $mail;

    /**
     * @ORM\Column(type="string", length=255)
     * @Groups({"association"})
     * @Assert\NotBlank
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $adress;

        /**
     * @ORM\Column(type="integer", nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     * @ZipCode(iso="FR")
     */
    private $zipcode;

    /**
     * @ORM\Column(type="string", length=100, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $city;

    /**
     * @ORM\Column(type="string", length=180)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     * @Assert\NotBlank
     */
    private $department;

    /**
     * @ORM\Column(type="string", length=180, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $region;

    /**
     * @ORM\Column(type="string", nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $phone_number;

    /**
     * @ORM\Column(type="text", nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $description;

    /**
     * @ORM\Column(type="boolean")
     * @Groups({"association"})
     */
    private $status;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $picture;

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"user"})
     * @Groups({"search"})
     */
    private $website;

    /**
     * @ORM\Column(type="json")
     * @groups({"user"})
     */
    private $roles = [];

    /**
     * @ORM\Column(type="string", length=255, nullable=true)
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"search"})
     */
    private $slug;

    /**
     * @ORM\OneToMany(targetEntity=Animal::class, mappedBy="user", orphanRemoval=true)
     * @Groups({"association"})
     * @Groups({"search"})
     */
    private $animals;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="userPost")
     */
    private $postReview;

    /**
     * @ORM\OneToMany(targetEntity=Review::class, mappedBy="userReceiver")
     */
    private $receivesReview;

    /**
     * @ORM\ManyToMany(targetEntity=Species::class, inversedBy="users")
     * @Groups({"list_associations"})
     * @Groups({"association"})
     * @Groups({"search"})
     * @Groups({"user"})
     */
    private $species;

    public function __construct()
    {
        $this->status = "true";

        $this->animals = new ArrayCollection();
        $this->postReview = new ArrayCollection();
        $this->receivesReview = new ArrayCollection();
        $this->species = new ArrayCollection();
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?string
    {
        return $this->type;
    }

    public function setType(string $type): self
    {
        $this->type = $type;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(?string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(?string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getSiret(): ?string
    {
        return $this->siret;
    }

    public function setSiret(?string $siret): self
    {
        $this->siret = $siret;

        return $this;
    }

    public function getMail(): ?string
    {
        return $this->mail;
    }

    public function setMail(string $mail): self
    {
        $this->mail = $mail;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(?string $adress): self
    {
        $this->adress = $adress;

        return $this;
    }

    public function getZipcode(): ?int
    {
        return $this->zipcode;
    }

    public function setZipcode(?int $zipcode): self
    {
        $this->zipcode = $zipcode;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): self
    {
        $this->city = $city;

        return $this;
    }

    public function getDepartment(): ?string
    {
        return $this->department;
    }

    public function setDepartment(?string $department): self
    {
        $this->department = $department;

        return $this;
    }

    public function getRegion(): ?string
    {
        return $this->region;
    }

    public function setRegion(?string $region): self
    {
        $this->region = $region;

        return $this;
    }

    public function getPhoneNumber(): ?string
    {
        return $this->phone_number;
    }

    public function setPhoneNumber(?string $phone_number): self
    {
        $this->phone_number = $phone_number;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getStatus(): ?bool
    {
        return $this->status;
    }

    public function setStatus(bool $status): self
    {
        $this->status = $status;

        return $this;
    }

    public function getPicture(): ?string
    {
        return $this->picture;
    }

    public function setPicture(?string $picture): self
    {
        $this->picture = $picture;

        return $this;
    }

    public function getWebsite(): ?string
    {
        return $this->website;
    }

    public function setWebsite(?string $website): self
    {
        $this->website = $website;

        return $this;
    }

    public function getSlug(): ?string
    {
        return $this->slug;
    }

    public function setSlug(?string $slug): self
    {
        $this->slug = $slug;

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
            $animal->setUser($this);
        }

        return $this;
    }

    public function removeAnimal(Animal $animal): self
    {
        if ($this->animals->removeElement($animal)) {
            // set the owning side to null (unless already changed)
            if ($animal->getUser() === $this) {
                $animal->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getPostReview(): Collection
    {
        return $this->postReview;
    }

    public function addPostReview(Review $postReview): self
    {
        if (!$this->postReview->contains($postReview)) {
            $this->postReview[] = $postReview;
            $postReview->setUserPost($this);
        }

        return $this;
    }

    public function removePostReview(Review $postReview): self
    {
        if ($this->postReview->removeElement($postReview)) {
            // set the owning side to null (unless already changed)
            if ($postReview->getUserPost() === $this) {
                $postReview->setUserPost(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Review>
     */
    public function getReceivesReview(): Collection
    {
        return $this->receivesReview;
    }

    public function addReceivesReview(Review $receivesReview): self
    {
        if (!$this->receivesReview->contains($receivesReview)) {
            $this->receivesReview[] = $receivesReview;
            $receivesReview->setUserReceiver($this);
        }

        return $this;
    }

    public function removeReceivesReview(Review $receivesReview): self
    {
        if ($this->receivesReview->removeElement($receivesReview)) {
            // set the owning side to null (unless already changed)
            if ($receivesReview->getUserReceiver() === $this) {
                $receivesReview->setUserReceiver(null);
            }
        }

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->mail;
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername(): string
    {
        return (string) $this->mail;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        if ($this->type === 'Association'){
            $roles[] = 'ROLE_ASSO';
        } elseif ($this->type === 'Particular' || $this->type === 'Particulier') {
            $roles[] = 'ROLE_USER';
        } else if ($this->type === 'Administrateur') {
            $role[] = 'ROLE_ADMIN';
        }

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */
    public function getSalt(): ?string
    {
        return null;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    /**
     * @return Collection<int, Species>
     */
    public function getSpecies(): Collection
    {
        return $this->species;
    }

    public function addSpecies(Species $species): self
    {
        if (!$this->species->contains($species)) {
            $this->species[] = $species;
        }

        return $this;
    }

    public function removeSpecies(Species $species): self
    {
        $this->species->removeElement($species);

        return $this;
    }

}
