<?php

namespace App\Entity;

use App\Repository\BookRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: BookRepository::class)]
class Book
{
    public const GROUP_CREATE = 'createBook';
    public const GROUP_UPDATE = 'updateBook';

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column()]
    private ?int $id = null;

    #[Assert\NotBlank(groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[Assert\Positive(groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[Assert\Type('string', groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[Assert\NotBlank(groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[Assert\Type('integer', groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[Assert\GreaterThan(value: 1900, groups: [self::GROUP_CREATE, self::GROUP_UPDATE])]
    #[ORM\Column]
    private ?int $year = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(?int $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }
}
