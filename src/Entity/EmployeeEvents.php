<?php

namespace App\Entity;

use App\Repository\EmployeeEventsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=EmployeeEventsRepository::class)
 */
class EmployeeEvents
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\ManyToOne(targetEntity=AppUser::class, inversedBy="employeeEvents")
     */
    private $user_id;

    /**
     * @ORM\ManyToOne(targetEntity=Event::class, inversedBy="employeeEvents")
     */
    private $event_id;

    /**
     * @ORM\Column(type="string", length=10)
     */
    private $entry;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserId(): ?AppUser
    {
        return $this->user_id;
    }

    public function setUserId(?AppUser $user_id): self
    {
        $this->user_id = $user_id;

        return $this;
    }

    public function getEventId(): ?Event
    {
        return $this->event_id;
    }

    public function setEventId(?Event $event_id): self
    {
        $this->event_id = $event_id;

        return $this;
    }

    public function getEntry(): ?string
    {
        return $this->entry;
    }

    public function setEntry(string $entry): self
    {
        $this->entry = $entry;

        return $this;
    }
}
