<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\FilmRepository")
 */
class Film
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
     * @ORM\Column(type="string", length=220)
     */
    private $title;

    /**
     * @ORM\Column(type="integer", nullable=true)
     */
    private $duration;

    /**
     * @ORM\Column(type="integer")
     */
    private $year;

    /**
     * @ORM\Column(type="integer")
     */
    private $id_genre;

    /**
     * @ORM\Column(type="blob", nullable=true)
     */
    private $cover;

    /**
     * @ORM\Column(type="text")
     */
    private $cover_type;

    public function getCoverType(){
        return $this->cover_type;
    }

    public function setCoverType(string $coverType){
        $this->cover_type = $coverType;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): self
    {
        $this->title = $title;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(?int $duration): self
    {
        $this->duration = $duration;

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

    public function getIdGenre(): ?int
    {
        return $this->id_genre;
    }

    public function setIdGenre(int $id_genre): self
    {
        $this->id_genre = $id_genre;

        return $this;
    }

    public function getCover()
    {
        return $this->cover;
    }

    public function setCover($cover): self
    {
        $this->cover = $cover;

        return $this;
    }
}
