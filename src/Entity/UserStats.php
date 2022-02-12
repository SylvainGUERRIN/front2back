<?php

namespace App\Entity;

use App\Repository\UserStatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Stats.
 *
 * @ORM\Entity(repositoryClass=UserStatsRepository::class)
 */
class UserStats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;
    /**
     * @ORM\Column(type="date_immutable")
     */
    private $lastConnectionAt;

    /**
     * @ORM\Column(type="json", nullable=true)
     */
    private array $tagsCounter = [];

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\User", mappedBy="stats")
     */
    protected ?User $user;

//    /**
//     * @ORM\OneToOne(targetEntity="App\Entity\Tag", mappedBy="stats")
//     */
//    protected ?Tag $tag;

    //getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return mixed
     */
    public function getLastConnectionAt(): ?\DateTimeInterface
    {
        return $this->lastConnectionAt;
    }

    /**
     * @param mixed $lastConnectionAt
     *
     * @return $this
     */
    public function setLastConnectionAt(\DateTimeInterface $lastConnectionAt): self
    {
        $this->lastConnectionAt = $lastConnectionAt;

        return $this;
    }

    public function getTagsCounter(): ?array
    {
        return $this->tagsCounter;
    }

    public function setTagsCounter(?array $tagsCounter): self
    {
        $this->tagsCounter = $tagsCounter;

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
        $newStats = null === $user ? null : $this;
        if ($user->getStats() !== $newStats) {
            $user->setStats($newStats);
        }

        return $this;
    }

//    public function getTag(): ?Tag
//    {
//        return $this->tag;
//    }
//
//    public function setTag(?Tag $tag): self
//    {
//        $this->tag = $tag;
//
//        // set (or unset) the owning side of the relation if necessary
//        $newStats = null === $tag ? null : $this;
//        if ($tag->getStats() !== $newStats) {
//            $tag->setStats($newStats);
//        }
//
//        return $this;
//    }
}
