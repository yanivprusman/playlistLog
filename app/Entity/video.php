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
    private int $id;
    
    #[Column(nullable:true)]
    private ?int $theIndex;
    
    #[Column(nullable:true)]
    private ?bool $seen;
    
    #[Column(nullable:true)]
    private ?bool $rewatch;
    
    #[Column(nullable:true)]
    private ?string $title;

    #[Column(name:'paused_at',nullable:true)]
    private ?string $pausedAt;

    #[Column(nullable:true)]
    private ?string $link;

    #[ManyToOne(inversedBy:'videos')]
    private Playlist $playlist;
    
    #[Column(name:'video_id',nullable:true)]
    private ?string $videoId;

    #[Column(nullable:true)]
    private ?string $length;

    public function __construct()
    {
        $this->theIndex = null;
        $this->seen = null;
        $this->rewatch = null;
        $this->title = null;
        $this->pausedAt = null;
        $this->link = null;
        $this->videoId = null;
        $this->length = null;
    }

    public function getId(): int
    {
        return $this->id;
    }
    
    public function getIndex(): ?int
    {
        return $this->theIndex;
    }

    public function setIndex(int $index): Video
    {
        $this->theIndex = $index;
        return $this;
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

    public function getVideoId(): string
    {
        return $this->videoId;
    }

    public function setVideoId(string $videoId): Video
    {
        $this->videoId = $videoId;

        return $this;
    }

    public function getPlaylist(): Playlist
    {
        return $this->playlist;
    }

    public function setPlaylist(Playlist $playlist): Video
    {
        $this->playlist = $playlist;

        return $this;
    }

    public function getSeen(): ?bool
    {
        return $this->seen;
    }

    public function setSeen(bool $seen): Video
    {
        $this->seen = $seen;

        return $this;
    }

    public function getPausedAt(): ?string
    {
        return $this->pausedAt;
    }

    public function setPausedAt(string $pausedAt): Video
    {
        $this->pausedAt = $pausedAt;

        return $this;
    }

    public function getReWatch(): ?bool
    {
        return $this->rewatch;
    }

    public function setReWatch(bool $rewatch): Video
    {
        $this->rewatch = $rewatch;

        return $this;
    }

    public function getLength(): ?string
    {
        return $this->length;
    }

    public function setLength(string $length): Video
    {
        $this->length = $length;

        return $this;
    }

    public function getLink(): ?string
    {
        return $this->link;
    }

    public function setLink(string $link): Video
    {
        $this->link = $link;

        return $this;
    }

}
