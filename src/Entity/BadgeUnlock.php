<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BadgeUnlockRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass=BadgeUnlockRepository::class)
 */
class BadgeUnlock
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
    protected string $user_id;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $badge_id;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $badge_unlocked_at;

    /**
     * @return int|null
     */
    public function getId(): ?int
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getUserId(): string
    {
        return $this->user_id;
    }

    /**
     * @param string $userId
     * @return BadgeUnlock
     */
    public function setUserId(string $userId): BadgeUnlock
    {
        $this->user_id = $userId;
        return $this;
    }

    /**
     * @return string
     */
    public function getBadgeId(): string
    {
        return $this->badge_id;
    }

    /**
     * @param string $badgeId
     * @return BadgeUnlock
     */
    public function setBadgeId(string $badgeId): BadgeUnlock
    {
        $this->badge_id = $badgeId;
        return $this;
    }

    public function getBadgeUnlockedAt(): ?\DateTimeInterface
    {
        return $this->badge_unlocked_at;
    }

    public function setBadgeUnlockedAt(\DateTimeInterface $badge_unlocked_at): self
    {
        $this->badge_unlocked_at = $badge_unlocked_at;

        return $this;
    }

//    public function __toString()
//    {
//        return (string) $this->getUrlImage();
//    }
}
