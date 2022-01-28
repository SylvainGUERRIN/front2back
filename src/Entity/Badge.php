<?php

declare(strict_types=1);

namespace App\Entity;

use App\Repository\BadgeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * @ORM\Entity(repositoryClass=BadgeRepository::class)
 * @ORM\Table(name="`badge`")
 * @UniqueEntity(fields={"name"}, message="Cette valeur est déjà utilisée.")
 */
class Badge
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
    protected string $name;

    /**
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="badge_image", fileNameProperty="url_image")
     */
    private ?File $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected ?string $url_image;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $action_name;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $action_delimiter;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="badges")
//     */
//    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="badges")
     */
    private ArrayCollection $users;

    public function __construct()
    {
        $this->users = new ArrayCollection();
    }

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
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     * @return Badge
     */
    public function setName(string $name): Badge
    {
        $this->name = $name;
        return $this;
    }

    /**
     * @return File|null
     */
    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    /**
     * @param File|null $imageFile
     * @return Badge
     */
    public function setImageFile(?File $imageFile): Badge
    {
        $this->imageFile = $imageFile;
        return $this;
    }

    /**
     * @return string|null
     */
    public function getUrlImage(): ?string
    {
        return $this->url_image;
    }

    /**
     * @param string|null $url_image
     * @return Badge
     */
    public function setUrlImage(?string $url_image): Badge
    {
        $this->url_image = $url_image;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionName(): string
    {
        return $this->action_name;
    }

    /**
     * @param string $action_name
     * @return Badge
     */
    public function setActionName(string $action_name): Badge
    {
        $this->action_name = $action_name;
        return $this;
    }

    /**
     * @return string
     */
    public function getActionDelimiter(): string
    {
        return $this->action_delimiter;
    }

    /**
     * @param string $action_delimiter
     * @return Badge
     */
    public function setActionDelimiter(string $action_delimiter): Badge
    {
        $this->action_delimiter = $action_delimiter;
        return $this;
    }

    /**
     * @return Collection|User[]
     */
    public function getUsers(): Collection
    {
        return $this->users;
    }

    public function addUser(User $user): self
    {
        if (!$this->users->contains($user)) {
            $this->users[] = $user;
            $user->setBadges($this);
        }

        return $this;
    }

    public function removeUser(User $user): User
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
            // set the owning side to null (unless already changed)
            if ($user->getBadges() === $this) {
                $user->setBadges(null);
            }
        }

        return $this;
    }
}
