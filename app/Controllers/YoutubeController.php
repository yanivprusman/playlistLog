<?php

declare(strict_types = 1);

namespace App\Controllers;

use DateInterval;
use App\Entity\User;
use G_H_PROJECTS_INCLUDE\G_h_projects_include;
use App\Entity\Playlist;
use App\Entity\Video;
use App\TutorialOldVersion;
use Psr\Http\Message\ServerRequestInterface as Request;
use Psr\Http\Message\ResponseInterface as Response;
use Slim\Views\Twig;
use Doctrine\ORM\EntityManager;
use App\Contracts\SessionInterface;
class YoutubeController
{
    private bool $setup = true;
    public function __construct(
        private readonly Twig $twig,
        private readonly EntityManager $entityManager,
        private readonly SessionInterface $session
        )
    {
    }

    // $this->displayPlaylist($playlist);
    // $playlist_id = 'id_101';
    // $title = YoutubeHelper::getTitleFromListId($playlist_id);
    // $playlist_id = 'asdf';
    // return $this->twig->render($response, 'setup.twig',data:['title'=>'title']);
    
    public function index(Request $request, Response $response): Response
    {   
        // $imagePath = 'https://i.ytimg.com/vi/D1Ab-iP8Ra0/default.jpg';
        // echo '<img src="' . $imagePath .'" alt="Description of the image">';

        $queryParams = $request->getQueryParams();
        
        if ((isset($queryParams['action'])) && ($queryParams['action']==='remove')) {
            $playlistId = $queryParams['playlist_id'];
            $this->removePlaylist($playlistId);
        }
        if($this->setup){
            $this->setup();
            // var_dump($userId);
            // return $response;
        }
        // $userId =  $this->session->get('user');
        $user =  $request->getAttribute('user');
        $playlist_id = isset($_GET['playlist_id']) ? $_GET['playlist_id'] : null;
        if ($playlist_id){// if user entered a playlist id
            // search for playlistId in database
            $playlist =$this->entityManager->getRepository(Playlist::class)->findBy([
                'playlistId'=>$playlist_id, 
                'user'=>$user->getId()])[0]??null;
            if($playlist){ //if exists load from database
                $playlist = $playlist->getVideos()->toArray();
                $this->echoGHProjects();
                $tutorialOldVersion = new  TutorialOldVersion($playlist);
            }else{// if not exists load from api
                $apiPlaylist = YoutubeHelper::getPlaylistFromListId($playlist_id);
                if(count($apiPlaylist)===0){ /** if not exist in api error */
                    echo 'wrong playlist id' . PHP_EOL;
                }else{ // add playlistrecord and display it  
                    // $user = $request->getAttribute('user');
                    $this->addPlaylistFromApi(apiPlaylist:$apiPlaylist,playlist_id:$playlist_id,user:$user);
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
    public function action(Request $request, Response $response): Response{
        $user =  $request->getAttribute('user');
        $fetchDecoded = json_decode(file_get_contents('php://input'), true);//bool ascociative array
        $row = $fetchDecoded['methodArguments']['row'];
        $action = $fetchDecoded['methodArguments']['action'];
        $value = $fetchDecoded['methodArguments']['value'];
        $playlistId = $fetchDecoded['methodArguments']['playlistId'];
        $playlist = $this->entityManager->getRepository(Playlist::class)->findBy([
            'playlistId'=>$playlistId, 
            'user'=>$user->getId()])[0]??null;
        $video = $this->entityManager->getRepository(Video::class)->findBy([
            'playlist'=>$playlist,
            'theIndex'=>$row
            ])[0];
        switch($action){
            case 'toggleSeen':
                $video->setSeen($value===1);
                echo json_encode([''=>'']);
                break;
            case 'toggleReWatch':
                $video->setReWatch($value===1);
                echo json_encode([''=>'']);
                break;
            case 'pausedAt':
                $video->setPausedAt($value);
                echo json_encode([''=>'']);
                break;
            }
        $this->entityManager->persist($playlist);
        $this->entityManager->flush();
        return $response;
    }
    public function echoGHProjects(){
        $root = dirname(__DIR__) . DIRECTORY_SEPARATOR;
        // $g_h_root = dirname($root) . "/101_include" . DIRECTORY_SEPARATOR;
        $g_h_root = dirname($root) . "/include" . DIRECTORY_SEPARATOR;
        G_h_projects_include::getInstance($g_h_root)->echo();

    }
    public function addPlaylistFromApi(array $apiPlaylist, string $playlist_id,User $user){
        $playlist = new Playlist();
        $playlist->setTitle($apiPlaylist[0]['snippet']['channelTitle']);
        $playlist->setPlaylistId($playlist_id);
        $playlist->setUser($user);
        $this->entityManager->persist($playlist);
        foreach($apiPlaylist as $apiVideo){
            $videoInfo = YoutubeHelper::getVideoInfo(
                videoId:$apiVideo['snippet']['resourceId']['videoId'],
                apiKey:(new YoutubeHelper())->apiKey);
            $video = (new Video())
                ->setPlaylist($playlist)
                ->setVideoId($apiVideo['snippet']['resourceId']['videoId'])
                ->setTitle($apiVideo['snippet']['title'])
                ->setLength((string)$videoInfo['durationInSeconds'])
                ->setIndex($apiVideo['snippet']['position']);
            $playlist->addVideo($video);
        }
        $this->entityManager->flush();
        $playlist = $playlist->getVideos()->toArray();
        $this->echoGHProjects();
        $tutorialOldVersion = new  TutorialOldVersion($playlist);    
    }
    public function setup(){
        $this->setupRemovePlaylist('PLfq-wQambEIdTavNPl3j8kBIV9zv7HjbV');
        // $this->setupRemovePlaylist('PLkahZjV5wKe-lnPv1TseLsmMexz3IO81Q');
        // exit;
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
    public function setupRemovePlaylist(string $playlistId=''){
        
        // $playlistId = 'RDQMgEzdN5RuCXE';                   //200+
        // $playlistId = 'PLkahZjV5wKe-lnPv1TseLsmMexz3IO81Q';//12
        // $playlistId = 'PLr3d3QYzkw2xabQRUpcZ_IBk9W50M9pe-';//138
        $playlist =$this->entityManager->getRepository(Playlist::class)->findBy(
            ['playlistId'=>$playlistId])[0]??null;
        if($playlist){ 
            $this->entityManager->remove($playlist);
            $this->entityManager->flush();
        }

    }
    public function removePlaylist(string $playlistId=''){
        $playlist =$this->entityManager->getRepository(Playlist::class)->findBy(
            ['playlistId'=>$playlistId])[0]??null;
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
    static $api_base = 'https://www.googleapis.com/youtube/v3/videos';
    static $thumbnail_base = 'https://i.ytimg.com/vi/';
    static $thumbnailDefault = '/default.jpg';
    static $thumbnailMqDefault = '/mqDefault.jpg';
    static $thumbnailHqDefault = '/hqDefault.jpg';
    static $thumbnailSdDefault = '/sdDefault.jpg';
    static $thumbnailMaxresDefault = '/maxresDefault.jpg';
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
        $apiUrl = "https://www.googleapis.com/youtube/v3/playlistItems?part=snippet,contentDetails&playlistId=$playlistId&key=$apiKey&maxResults=200";
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
        // } while (false);   
         
        return $allResults;
    }
    public static function getVideoInfo($videoId,$apiKey)
    {
        $params =[
            'part' => 'contentDetails',
            'id' => $videoId,
            'key' => $apiKey,
        ];

        $apiUrl = self::$api_base . '?' . http_build_query($params);
        $result = json_decode(@file_get_contents($apiUrl), true);

        if(empty($result['items'][0]['contentDetails']))
            return null;
        $info = $result['items'][0]['contentDetails'];

        $interval = new DateInterval($info['duration']);
        
        $smallInfo['durationInSeconds'] = $interval->h * 3600 + $interval->i * 60 + $interval->s;
        $smallInfo['thumbnail']         = self::$thumbnail_base . $videoId;

        return $smallInfo;
    }
}


