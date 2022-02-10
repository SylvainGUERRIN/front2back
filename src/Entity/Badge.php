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
 * @Vich\Uploadable()
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
     * @ORM\Column(type="string", length=255, nullable=true)
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

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    protected string $action_quantity;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $role_delimiter;

    /**
     * @ORM\Column(type="datetime")
     */
    private ?\DateTimeInterface $badge_created_at;

    /**
     * @ORM\Column(type="datetime", nullable=true)
     */
    private ?\DateTimeInterface $badge_modified_at;

//    /**
//     * @ORM\OneToMany(targetEntity="App\Entity\User", mappedBy="badges")
//     */
//    private $users;

    /**
     * @ORM\ManyToMany(targetEntity="App\Entity\User", mappedBy="badges")
     */
    private Collection $users;

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
        if (null !== $imageFile) {
            $this->badge_modified_at = new \DateTime('now');
        }
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


    public function getActionQuantity(): string
    {
        return $this->action_quantity;
    }

    public function setActionQuantity(string $action_quantity): Badge
    {
        $this->action_quantity = $action_quantity;
        return $this;
    }

    /**
     * @return string
     */
    public function getRoleDelimiter(): string
    {
        return $this->role_delimiter;
    }

    /**
     * @param string $role_delimiter
     * @return Badge
     */
    public function setRoleDelimiter(string $role_delimiter): Badge
    {
        $this->role_delimiter = $role_delimiter;
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
        }

        return $this;
    }

    public function removeUser(User $user): User
    {
        if ($this->users->contains($user)) {
            $this->users->removeElement($user);
        }

        return $this;
    }

    public function getBadgeCreatedAt(): ?\DateTimeInterface
    {
        return $this->badge_created_at;
    }

    public function setBadgeCreatedAt(\DateTimeInterface $badge_created_at): self
    {
        $this->badge_created_at = $badge_created_at;

        return $this;
    }

    public function getBadgeModifiedAt(): ?\DateTimeInterface
    {
        return $this->badge_modified_at;
    }

    public function setBadgeModifiedAt(?\DateTimeInterface $badge_modified_at): self
    {
        $this->badge_modified_at = $badge_modified_at;

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUrlImage();
    }
}
