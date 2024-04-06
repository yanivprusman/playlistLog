<?php

declare(strict_types = 1);

namespace App\Controllers;

use G_H_PROJECTS_INCLUDE\G_h_projects_include;
use App\Entity\Playlist;
use App\Entity\Video;
use App\Enum\AppEnvironment;
use App\TutorialOldVersion;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Doctrine\ORM\EntityManager;
class YoutubeController
{
    public function __construct(
        private readonly Twig $twig,
        private readonly EntityManager $entityManager,
        )
    {
    }

    // $this->displayPlaylist($playlist);
    // $playlist_id = 'id_101';
    // $title = YoutubeHelper::getTitleFromListId($playlist_id);
    // $playlist_id = 'asdf';
    // return $this->twig->render($response, 'setup.twig',data:['title'=>'title']);
    // $this->setup();
    
    public function index(Request $request, Response $response): Response
    {   
        $playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;
        if ($playlist_id){// if user entered a playlist id
            // search for playlistId in database
            $playlist =$this->entityManager->getRepository(Playlist::class)->findBy(
                ['playlistId'=>$playlist_id])[0]??null;
            if($playlist){ //if exists load from database
                $playlist = $playlist->getVideos()->toArray();
                $this->echoGHProjects();
                $tutorialOldVersion = new  TutorialOldVersion($playlist);
            }else{// if not exists load from api
                $apiPlaylist = YoutubeHelper::getPlaylistFromListId($playlist_id);
                if(count($apiPlaylist)===0){ /** if not exist in api error */
                    echo 'wrong playlist id' . PHP_EOL;
                }else{ // add playlistrecord and display it  
                    $this->addPlaylistFromApi(apiPlaylist:$apiPlaylist,playlist_id:$playlist_id);
                }
            }
            // var_dump($playList);
            // $html = 'asdf';

            // $x = G_h_projects_include::getInstance();
            // new G_h_projects_include();
            // $html = include __DIR__ . '/../playlistOldVersion.php';
            // $html = new Tutorial();
            return $response;
        }
        else{
            return $this->twig->render($response, 'insertPlaylist.twig');
        }
    }
    public function echoGHProjects(){
        $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        // $g_h_root = dirname($root) . "/101_include" . DIRECTORY_SEPARATOR;
        $g_h_root = dirname($root) . "/include" . DIRECTORY_SEPARATOR;
        G_h_projects_include::getInstance($g_h_root)->echo();

    }
    public function addPlaylistFromApi(array $apiPlaylist, string $playlist_id=''){
        $playlist = new Playlist();
        $playlist->setTitle($apiPlaylist[0]['snippet']['channelTitle']);
        $playlist->setPlaylistId($playlist_id);
        $this->entityManager->persist($playlist);
        foreach($apiPlaylist as $apiVideo){
            $video = (new Video())
                ->setPlaylist($playlist)
                ->setVideoId($apiVideo['snippet']['resourceId']['videoId'])
                ->setTitle($apiVideo['snippet']['title'])
                ->setLength('123')
                ->setIndex($apiVideo['snippet']['position']);
            $playlist->addVideo($video);
        }
        $this->entityManager->flush();
        $playlist = $playlist->getVideos()->toArray();
        $tutorialOldVersion = new  TutorialOldVersion($playlist);    
    }
    public function setup(){
        $this->setupRemovePlaylist();
        exit;
        // $video = new Video();
        // $video = new Class101();
        // var_dump($video);
        // $this->setupAddPlaylistRecord();
        // $x 0= $this->setupGetPlaylist();
        // $this->setupAddVideo();        
        // var_dump($x->getVideos()->count());
        // var_dump($x->getVideos()->get(0));
        // $x = $this->entityManager->getRepository(Playlist::class);
        // var_dump($x);
        // $playlist = new Playlist();
        // $playlist->setTitle('my playlist title');
        // $playlist->setPlaylistId('id_101');
        // $this->entityManager->persist($playlist);
        // $this->entityManager->flush();
    }
    public function setupRemovePlaylist(){
        $playlist_id = 'RDQMgEzdN5RuCXE';
        $playlist =$this->entityManager->getRepository(Playlist::class)->findBy(
            ['playlistId'=>$playlist_id])[0]??null;
        if($playlist){ 
            $this->entityManager->remove($playlist);
            $this->entityManager->flush();
        }

    }
    public function setupAddVideo(){
        $playlist = $this->setupGetPlaylist();
        $video = (new Video())
            ->setTitle('my video')
            ->setVideoId('videoId101')
            ->setPlaylist($playlist);
        $playlist->addVideo($video);
        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
    }
    public function setupGetPlaylist():?Playlist{
        $x =$this->entityManager->getRepository(Playlist::class)->findBy(
            ['playlistId'=>'id_101'])[0]??null;
        // var_dump($x);
        return $x;
    }
    public function setupAddPlaylistRecord(){
        // $x = $this->entityManager->getRepository(Playlist::class);
        // var_dump($x);
        if (false){
            $playlist = new Playlist();
            $playlist->setTitle('my title');
            $playlist->setPlaylistId('id_101');
            $this->entityManager->persist($playlist);
            $this->entityManager->flush();
        }else{

        }
    }
}

class YoutubeHelper{
    public function __construct(
        public ?string $playlistId = null,
        public ?string $apiKey = null,        
        ){
        if (!$apiKey){
            // $this->apiKey = 'PL4cUxeGkcC9jsz4LDYc6kv3ymONOKxwBU';
            $this->apiKey = 'AIzaSyBuSr11L-o8o2zRl0W0lCn-2WMGQd1Z8N0';
        }
        // if (!$playlistId){
        //     $this->playlistId = 'PLr3d3QYzkw2xabQRUpcZ_IBk9W50M9pe-';
        // }
    }
    public static function getListParameterFromUri($uri) {
        // Parse the query string from the URI
        $query = parse_url($uri, PHP_URL_QUERY);
        
        // Check if the query string is empty
        if ($query === null) {
            return null; // Return null if there are no query parameters
        }
        
        // Parse the query string into an associative array
        parse_str($query, $parameters);
        
        // Check if the 'list' parameter exists and return its value
        if (isset($parameters['list'])) {
            return $parameters['list'];
        } else {
            return null; // Return null if the 'list' parameter is not found
        }
    }
    public static function getPlaylistFromListId(
        ?string $playlistId = null,
        ?string $apiKey = null,        
        ):array{
        $youtubeHelper = new YoutubeHelper(playlistId:$playlistId,apiKey:$apiKey);
        $apiKey = $youtubeHelper->apiKey;
        $playlistId = $youtubeHelper->playlistId;
        $apiUrl = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet&playlistId=$playlistId&key=$apiKey";
        $allResults = [];
        // $response = file_get_contents($apiUrl);
        do {
            $response = @file_get_contents($apiUrl);
            if ($response) {
                $data = json_decode($response, true);
                if (isset($data['items'])) {
                    // Merge current page results with previous results
                    $allResults = array_merge($allResults, $data['items']);
                }
                // Check if there are more pages
                if (isset($data['nextPageToken'])) {
                    $nextPageToken = $data['nextPageToken'];
                    // Append pageToken parameter for the next page
                    $apiUrl .= "&pageToken=$nextPageToken";
                } else {
                    break; // No more pages, exit loop
                }
            } else {
                // echo "Error fetching data from the YouTube API.";
                return [];
            }
        } while (count($allResults)<200);    
        return $allResults;
    }
    // public static function getTitleFromListId(
    //     string $playlistId = null,
    //     string $apiKey = null,        
    //     ):string{
    //     $youtubeHelper = new YoutubeHelper(playlistId:$playlistId,apiKey:$apiKey);
    //     $apiKey = $youtubeHelper->apiKey;
    //     $playlistId = $youtubeHelper->playlistId;
    //     $apiUrl = "https://www.googleapis.com/youtube/v3/playlists?part=snippet&id=$playlistId&key=$apiKey";
    //     $ch = curl_init($apiUrl);
    //     curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    //     $response = curl_exec($ch);
    //     curl_close($ch);
    //     if ($response) {
    //         $data = json_decode($response, true);
        
    //         if (isset($data['items'][0]['snippet']['title'])) {
    //             $playlistTitle = $data['items'][0]['snippet']['title'];
    //             return "$playlistTitle";
    //         } else {
    //             return "Playlist not found or title not available.";
    //         }
    //     } else {
    //         echo "Error fetching data from the YouTube API.";
    //     }        
    // }
}


