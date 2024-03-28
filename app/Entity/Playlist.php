<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Contracts\UserInterface;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Event\LifecycleEventArgs;
use Doctrine\ORM\Mapping\Column;
use Doctrine\ORM\Mapping\Entity;
use Doctrine\ORM\Mapping\GeneratedValue;
use Doctrine\ORM\Mapping\HasLifecycleCallbacks;
use Doctrine\ORM\Mapping\Id;
use Doctrine\ORM\Mapping\ManyToMany;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('playlist')]
#[HasLifecycleCallbacks]
class Playlist 
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    private int $id;

    #[Column(unique:true,name:'playlist_id')]
    private string $playlistId;

    #[Column]
    private string $title;

    // #[ManyToMany(targetEntity:Video::class,mappedBy:'playlistId')]
    #[OneToMany(targetEntity:Video::class,mappedBy:'id')]
    private Collection $videos;

    public function __construct()
    {
    }

    public function getId(): int
    {
        return $this->id;
    }

    public function getTitle(): string
    {
        return $this->title;
    }

    public function setTitle(string $title): Playlist
    {
        $this->title = $title;

        return $this;
    }
}
