<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Avatar.
 *
 * @ORM\Entity(repositoryClass=AvatarRepository::class)
// * @ORM\Table(name="avatar")
 */
class Avatar
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $url;

    /**
     * @ORM\Column(type="date_immutable")
     */
    protected \DateTimeImmutable $updatedAt;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="avatar")
     */
    protected ?User $user;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUrl(): string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getUpdatedAt(): \DateTimeImmutable
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTimeImmutable $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function getUser(): ?User
    {
        return $this->user;
    }

    public function setUser(?User $user): self
    {
        $this->user = $user;

        // set (or unset) the owning side of the relation if necessary
        $newAvatar = null === $user ? null : $this;
        if ($user->getAvatar() !== $newAvatar) {
            $user->setAvatar($newAvatar);
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUrl();
    }
}
