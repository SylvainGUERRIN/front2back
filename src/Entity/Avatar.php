<?php

namespace App\Entity;

use App\Repository\AvatarRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\HttpFoundation\File\File;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Validator\Constraints as Assert;
use Vich\UploaderBundle\Mapping\Annotation as Vich;

/**
 * Class Avatar.
 *
 * @ORM\Entity(repositoryClass=AvatarRepository::class)
 * @Vich\Uploadable()
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
     * @var File|null
     * @Assert\Image(
     *     mimeTypes={"image/jpeg", "image/jpg", "image/png"}
     * )
     * @Vich\UploadableField(mapping="avatar_image", fileNameProperty="url")
     */
    private $imageFile;

    /**
     * @ORM\Column(type="string", length=255)
     */
    protected string $url;

    /**
     * @ORM\Column(type="datetime")
     */
    protected \DateTime $updatedAt;

    /**
     * @ORM\Column(type="boolean")
     */
    protected bool $validatedAt;

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

    public function getUpdatedAt(): \DateTime
    {
        return $this->updatedAt;
    }

    public function setUpdatedAt(\DateTime $updatedAt): self
    {
        $this->updatedAt = $updatedAt;

        return $this;
    }

    public function isValidatedAt(): bool
    {
        return $this->validatedAt;
    }

    public function setValidatedAt(bool $validatedAt): self
    {
        $this->validatedAt = $validatedAt;

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

    public function getImageFile(): ?File
    {
        return $this->imageFile;
    }

    public function setImageFile(?File $imageFile = null): Avatar
    {
        $this->imageFile = $imageFile;
        /*if($this->imageFile instanceof UploadedFile){
            $this->updated_at = new \DateTime('now');
        }*/
        if (null !== $imageFile) {
            $this->updatedAt = new \DateTime('now');
        }

        return $this;
    }

    public function __toString()
    {
        return (string) $this->getUrl();
    }
}
