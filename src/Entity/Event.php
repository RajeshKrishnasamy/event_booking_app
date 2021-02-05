<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

/**
 * @ORM\Entity(repositoryClass=EventRepository::class)
 */
class Event
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=100)
     */
    private $name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    private $description;

    /**
     * @ORM\Column(type="datetime")
     */
    private $date;

    /**
     * @ORM\Column(type="datetime")
     */
    public $start_time;

    /**
     * @ORM\Column(type="datetime")
     */
    public $end_time;

    /**
     * @ORM\Column(type="integer")
     *  @Assert\Range(
     *      min = 5,
     *      max = 100,
     *      minMessage = "Please enter minimum {{ min }} seats for an Event",
     *      maxMessage = "Maximum limit is {{ max }} seats for an Event"
     * )
     */
    private $seats;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="adminEvents")
     * 
     */
    private $admin;

    /**
     * @ORM\OneToMany(targetEntity=EmployeeEvents::class, mappedBy="event_id")
     */
    private $employeeEvents;

    /**
     * @ORM\Column(type="integer")
     */
    private $status;

    public function __construct()
    {
        $this->employeeEvents = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->start_time;
    }

    public function setStartTime(\DateTimeInterface $start_time): self
    {
        $this->start_time = $start_time;

        return $this;
    }

    public function getEndTime(): ?\DateTimeInterface
    {
        return $this->end_time;
    }

    public function setEndTime(\DateTimeInterface $end_time): self
    {
        $this->end_time = $end_time;

        return $this;
    }

    public function getSeats(): ?int
    {
        return $this->seats;
    }

    public function setSeats(int $seats): self
    {
        $this->seats = $seats;

        return $this;
    }

    public function getAdmin(): ?AppUser
    {
        return $this->admin;
    }

    public function setAdmin(?AppUser $admin): self
    {
        $this->admin = $admin;

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
            $employeeEvent->setEventId($this);
        }

        return $this;
    }

    public function removeEmployeeEvent(EmployeeEvents $employeeEvent): self
    {
        if ($this->employeeEvents->removeElement($employeeEvent)) {
            // set the owning side to null (unless already changed)
            if ($employeeEvent->getEventId() === $this) {
                $employeeEvent->setEventId(null);
            }
        }

        return $this;
    }

    public function getStatus(): ?int
    {
        return $this->status;
    }

    public function setStatus(int $status): self
    {
        $this->status = $status;

        return $this;
    }
}
