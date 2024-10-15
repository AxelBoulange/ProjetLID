<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?type $type = null;

    #[ORM\Column]
    private ?bool $want_teacher = null;

    #[ORM\Column]
    private ?bool $want_student = null;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $profile_picture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $dte_created = null;

    #[ORM\Column(length: 255)]
    private ?string $bigraphy = null;

    /**
     * @var Collection<int, Message>
     */
    #[ORM\OneToMany(targetEntity: Message::class, mappedBy: 'user')]
    private Collection $messages;

    /**
     * @var Collection<int, Report>
     */
    #[ORM\OneToMany(targetEntity: Report::class, mappedBy: 'user')]
    private Collection $reports;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
        $this->reports = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getType(): ?type
    {
        return $this->type;
    }

    public function setType(?type $type): static
    {
        $this->type = $type;

        return $this;
    }

    public function isWantTeacher(): ?bool
    {
        return $this->want_teacher;
    }

    public function setWantTeacher(bool $want_teacher): static
    {
        $this->want_teacher = $want_teacher;

        return $this;
    }

    public function isWantStudent(): ?bool
    {
        return $this->want_student;
    }

    public function setWantStudent(bool $want_student): static
    {
        $this->want_student = $want_student;

        return $this;
    }

    public function getProfilePicture()
    {
        return $this->profile_picture;
    }

    public function setProfilePicture($profile_picture): static
    {
        $this->profile_picture = $profile_picture;

        return $this;
    }

    public function getDteCreated(): ?\DateTimeInterface
    {
        return $this->dte_created;
    }

    public function setDteCreated(\DateTimeInterface $dte_created): static
    {
        $this->dte_created = $dte_created;

        return $this;
    }

    public function getBigraphy(): ?string
    {
        return $this->bigraphy;
    }

    public function setBigraphy(string $bigraphy): static
    {
        $this->bigraphy = $bigraphy;

        return $this;
    }

    /**
     * @return Collection<int, Message>
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    public function addMessage(Message $message): static
    {
        if (!$this->messages->contains($message)) {
            $this->messages->add($message);
            $message->setUser($this);
        }

        return $this;
    }

    public function removeMessage(Message $message): static
    {
        if ($this->messages->removeElement($message)) {
            // set the owning side to null (unless already changed)
            if ($message->getUser() === $this) {
                $message->setUser(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Report>
     */
    public function getReports(): Collection
    {
        return $this->reports;
    }

    public function addReport(Report $report): static
    {
        if (!$this->reports->contains($report)) {
            $this->reports->add($report);
            $report->setUser($this);
        }

        return $this;
    }

    public function removeReport(Report $report): static
    {
        if ($this->reports->removeElement($report)) {
            // set the owning side to null (unless already changed)
            if ($report->getUser() === $this) {
                $report->setUser(null);
            }
        }

        return $this;
    }
}
