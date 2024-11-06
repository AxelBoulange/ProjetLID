<?php

namespace App\Entity;

use AllowDynamicProperties;
use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints\Date;
use Symfony\Component\Validator\Constraints\DateTime;

#[AllowDynamicProperties] #[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: ['username'], message: 'There is already an account with this username')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: 'string')]
    private string $password;

    #[ORM\Column(type: 'string')]
    private string $username;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Type $type = null;

    #[ORM\Column]
    private bool $want_teacher = false;

    #[ORM\Column]
    private bool $want_student = true;

    #[ORM\Column(type: Types::BLOB, nullable: true)]
    private $profile_picture = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private \DateTimeInterface $dte_created;

    #[ORM\Column(length: 255)]
    private string $biography = "";

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

    public function getType(): ?Type
    {
        return $this->type;
    }

    public function setType(?Type $type): static
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

    public function setProfilePictureToDefault()
    {
        $stream = fopen('../assets/img/default_pp.png', 'r');
        $bufferSize = 4096; // 4 KB
        $data = '';

        while (!feof($stream)) {
            $chunk = stream_get_contents($stream, $bufferSize);
            $data .= $chunk;
        }
        fclose($stream);

        $this->profile_picture = $data;

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
    public function setDteCreatedAtCurrent(): static
    {
        $this->dte_created = new \DateTime();

        return $this;
    }

    public function getBiography(): ?string
    {
        return $this->biography;
    }

    public function setBiography(string $biography): static
    {
        $this->biography = $biography;

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

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function getRoles(): array
    {
        return [$this->type];
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null;
    }

    public function getUserIdentifier(): string
    {
        return (string) $this->username;
    }

    public function setPassword(string $password): void
    {
        $this->password = $password;
    }

    public function getUsername(): string
    {
        return $this->username;
    }

    public function setUsername(string $username): void
    {
        $this->username = $username;
    }
}
