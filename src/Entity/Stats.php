<?php

namespace App\Entity;

use App\Repository\StatsRepository;
use Doctrine\ORM\Mapping as ORM;

/**
 * Class Tag.
 *
 * @ORM\Entity(repositoryClass=StatsRepository::class)
 */
class Stats
{
    /**
     * @ORM\Id
     * @ORM\GeneratedValue
     * @ORM\Column(type="integer")
     */
    protected ?int $id;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $favorite_counter;

    /**
     * @ORM\Column(type="integer")
     */
    private ?int $number_of_views;

    /**
     * @ORM\OneToOne(targetEntity="App\Entity\Tag", mappedBy="stats")
     */
    protected ?Tag $tag;

    //getters and setters
    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFavoriteCounter()
    {
        return $this->favorite_counter;
    }

    public function setFavoriteCounter($favorite_counter): self
    {
        $this->favorite_counter = $favorite_counter;

        return $this;
    }

    public function getNumberOfViews()
    {
        return $this->number_of_views;
    }

    public function setNumberOfViews($number_of_views): self
    {
        $this->number_of_views = $number_of_views;

        return $this;
    }

    public function getTag(): ?Tag
    {
        return $this->tag;
    }

    public function setTag(?Tag $tag): self
    {
        $this->tag = $tag;

        // set (or unset) the owning side of the relation if necessary
        $newStats = null === $tag ? null : $this;
        if ($tag->getStats() !== $newStats) {
            $tag->setStats($newStats);
        }

        return $this;
    }
}
