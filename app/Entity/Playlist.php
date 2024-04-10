<?php

declare(strict_types = 1);

namespace App\Entity;

use App\Entity\User;
use Doctrine\ORM\Mapping\ManyToOne;
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

    #[Column(name:'playlist_id')]
    private string $playlistId;

    #[Column]
    private string $title;

    #[OneToMany(targetEntity:Video::class,mappedBy:'playlist',cascade:['persist','remove'])]
    private Collection $videos;

    #[ManyToOne(inversedBy:'playlists')]
    private User $user;

    public function __construct()
    {
        $this->videos = new ArrayCollection();
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
    public function getPlaylistId(): string
    {
        return $this->playlistId;
    }

    public function setPlaylistId(string $playlistId): Playlist
    {
        $this->playlistId = $playlistId;

        return $this;
    }
    
    public function addVideo(Video $video){
        $this->videos->add($video);

    }

    public function setVideos(Collection $videos): Playlist
    {
        $this->videos = $videos;

        return $this;
    }
    
    public function getVideos():Collection{
        
        return $this->videos;
    }

    public function setUser(User $user):Playlist{
        $this->user = $user;
        
        return $this;
    }

    public function getUser(){
        return $this->user;
    }
}
