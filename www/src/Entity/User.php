<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ORM\Table(name: '`user`')]
#[UniqueEntity(fields: ['email'], message: 'Email уже занят.')]
class User
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer")]
    private ?int $id = null;

    #[ORM\Column(type: "string", length: 255, unique: true)]
    #[Assert\NotBlank(message: "Заполните поле email")]
    #[Assert\Email(message: "Неверный формат email")]
    private ?string $email = null;

    #[ORM\Column(type: "string", length: 255)]
    #[Assert\NotBlank(message: "Заполните поле имя")]
    private ?string $name = null;

    #[ORM\Column(type: "integer")]
    #[Assert\GreaterThan(value: 0, message: "Неверный формат возраста")]
    private ?int $age = null;

    #[ORM\Column(type: "string", length: 10)]
    #[Assert\Choice(choices: ["male", "female"], message: "Неверное значение пола")]
    private ?string $sex = null;

    #[ORM\Column(type: "date")]
    private ?\DateTimeInterface $birthday = null;

    #[ORM\Column(type: "string", length: 20)]
    #[Assert\Regex(
        pattern: '/^(\(8|\+7)[\- ]?(\(?\d{3}\)?[\- ]?)?[\d\- ]{6,10}$/',
        message: 'Некорректный формат номера телефона'
    )]
    private ?string $phone = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $created_at = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $updated_at = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(?string $email): void
    {
        $this->email = $email;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(?string $name): void
    {
        $this->name = $name;
    }

    public function getAge(): ?int
    {
        return $this->age;
    }

    public function setAge(?int $age): void
    {
        $this->age = $age;
    }

    public function getSex(): ?string
    {
        return $this->sex;
    }

    public function setSex(?string $sex): void
    {
        $this->sex = $sex;
    }

    public function getBirthday(): ?\DateTimeInterface
    {
        return $this->birthday;
    }

    public function setBirthday(?\DateTimeInterface $birthday): void
    {
        $this->birthday = $birthday;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): void
    {
        $this->phone = $phone;
    }

    public function getCreatedAt(): ?\DateTimeInterface
    {
        return $this->created_at;
    }

    public function setCreatedAt(?\DateTimeInterface $created_at): void
    {
        $this->created_at = $created_at;
    }

    public function getUpdatedAt(): ?\DateTimeInterface
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(?\DateTimeInterface $updated_at): void
    {
        $this->updated_at = $updated_at;
    }
}
