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
use Doctrine\ORM\Mapping\ManyToOne;
use Doctrine\ORM\Mapping\OneToMany;
use Doctrine\ORM\Mapping\PrePersist;
use Doctrine\ORM\Mapping\PreUpdate;
use Doctrine\ORM\Mapping\Table;

#[Entity, Table('video')]
#[HasLifecycleCallbacks]
class Video 
{
    #[Id, Column(options: ['unsigned' => true]), GeneratedValue]
    #[ManyToOne(targetEntity:Playlist::class, inversedBy:'videos')]
    private int $id;

    #[Column(unique:true,name:'playlist_id')]
    private string $playlistId;

    #[Column(name:'video_id',unique:false)]
    private string $videoId;

    #[Column]
    private string $title;

    #[Column]
    private bool $seen;

    #[Column]
    private bool $rewatch;

    #[Column(name:'paused_at')]
    private string $pausedAt;

    #[Column]
    private string $length;
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

    public function setTitle(string $title): Video
    {
        $this->title = $title;

        return $this;
    }
}
