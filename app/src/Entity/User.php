<?php

namespace App\Entity;

use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;
use App\Repository\UserRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[UniqueEntity(fields: ['email'], message: 'You already have account')]
#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 128, nullable: false)]
    private ?string $email = null;

    #[ORM\Column(length: 255, unique: true)]
    private ?string $password = null;

    #[ORM\Column(type: Types::JSON)]
    private ?array $roles = null;

    #[Assert\NotBlank]
    #[Assert\NotBlank(message: "Пароль не может быть пустым")]
    #[Assert\Length(min: 6, minMessage: "Пароль должен содержать минимум 6 символов")]
    private string $plainPassword = '';

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $confirmationCode = null;

    #[ORM\Column(options: ["default" => 0])]
    private ?bool $enabled = false;

    public const ROLE_USER = 'ROLE_USER';

    public function __construct()
    {
        $this->roles = [self::ROLE_USER];
    }


    public function getId(): ?int
    {
        return $this->id;
    }

    public function getUserIdentifier(): string
    {
        return $this->email;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = '';
    }

    public function getRoles(): array
    {
        return [
            'ROLE_USER'
        ];
    }

    public function setRoles(?array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    public function getConfirmationCode(): string
    {
        return $this->confirmationCode;
    }

    public function setConfirmationCode(string $confirmationCode): self
    {
        $this->confirmationCode = $confirmationCode;

        return $this;
    }

    public function getEnabled(): bool
    {
        return $this->enabled;
    }

    public function setEnable(bool $enabled): self
    {
        $this->enabled = $enabled;

        return $this;
    }

    public function getPlainPassword(): string
    {
        return $this->plainPassword ?? '';
    }

    public function setPlainPassword(string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;
        return $this;
    }
    
}
