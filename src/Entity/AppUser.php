<?php

namespace App\Entity;

use App\Repository\AppUserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\UserInterface;

/**
 * @ORM\Entity(repositoryClass=AppUserRepository::class)
 */
class AppUser implements UserInterface
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=180, unique=true)
     */
    private $email;

    /**
     * @ORM\Column(type="json")
     */
    private $roles = [];

    /**
     * @var string The hashed password
     * @ORM\Column(type="string")
     */
    private $password;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $first_name;

    /**
     * @ORM\Column(type="string", length=50)
     */
    private $last_name;

    /**
     * @ORM\OneToMany(targetEntity=Event::class, mappedBy="admin")
     */
    private $adminEvents;

    /**
     * @ORM\OneToMany(targetEntity=EmployeeEvents::class, mappedBy="user_id")
     */
    private $employeeEvents;

    public function __construct()
    {
        $this->adminEvents = new ArrayCollection();
        $this->employeeEvents = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUsername(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getPassword(): string
    {
        return (string) $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function getSalt()
    {
        // not needed when using the "bcrypt" algorithm in security.yaml
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): self
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): self
    {
        $this->last_name = $last_name;

        return $this;
    }

    /**
     * @return Collection|Event[]
     */
    public function getAdminEvents(): Collection
    {
        return $this->adminEvents;
    }

    public function addAdminEvent(Event $adminEvent): self
    {
        if (!$this->adminEvents->contains($adminEvent)) {
            $this->adminEvents[] = $adminEvent;
            $adminEvent->setAdmin($this);
        }

        return $this;
    }

    public function removeAdminEvent(Event $adminEvent): self
    {
        if ($this->adminEvents->removeElement($adminEvent)) {
            // set the owning side to null (unless already changed)
            if ($adminEvent->getAdmin() === $this) {
                $adminEvent->setAdmin(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection|EmployeeEvents[]
     */
    public function getEmployeeEvents(): Collection
    {
        return $this->employeeEvents;
    }

    public function addEmployeeEvent(EmployeeEvents $employeeEvent): self
    {
        if (!$this->employeeEvents->contains($employeeEvent)) {
            $this->employeeEvents[] = $employeeEvent;
            $employeeEvent->setUserId($this);
        }

        return $this;
    }

    public function removeEmployeeEvent(EmployeeEvents $employeeEvent): self
    {
        if ($this->employeeEvents->removeElement($employeeEvent)) {
            // set the owning side to null (unless already changed)
            if ($employeeEvent->getUserId() === $this) {
                $employeeEvent->setUserId(null);
            }
        }

        return $this;
    }
}
