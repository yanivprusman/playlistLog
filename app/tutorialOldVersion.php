<?php
declare(strict_types = 1);
namespace App;
require_once __DIR__ . '/../vendor/autoload.php';
use G_H_PROJECTS_INCLUDE\G_h_projects_include;
use G_H_PROJECTS_INCLUDE\Fetch;
use G_H_PROJECTS_INCLUDE\DoSql;
class TutorialOldVersion2{
    public function __construct(){
        echo 'in TutorialOldVersion' . PHP_EOL;
        echo 'B4' . PHP_EOL;
        new G_h_projects_include();
        $fetch = new Fetch(class: DoSql::class,method: 'doSql',echoReturn: false);

        echo 'After' . PHP_EOL;
    }
}
class TutorialOldVersion{
    public function __construct(
        private array $playList=[],
        private string $title='')
    {
        new G_h_projects_include();
?>
<!DOCTYPE html>
<html>
    <head>
        <!-- <title>program with gio</title> -->
        <title><?php echo $this->title;?></title>
        <style>
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: center;
            }

            table tr th, table tr td {
                padding: 5px;
                border: 1px #eee solid;
            }

            tfoot tr th, tfoot tr td {
                font-size: 20px;
            }

            tfoot tr th {
                text-align: right;
            }
            #context-menu {
                display: none;
                position: absolute;
                background-color: #f9f9f9;
                border: 1px solid #ccc;
                padding: 8px;
                z-index: 1000;
            }
            #context-menu ul {
                list-style: none;
                padding: 0;
                margin: 0;
            }
            #context-menu ul li {
                margin-bottom: 4px;
            }
            #context-menu ul li a {
                display: block;
                padding: 4px;
                text-decoration: none;
                color: #333;
            }
        </style>
    </head>
    <body>
    <div id="context-menu">
        <ul>
            <li id="context-menu-title" style="color:brown; text-decoration:underline;"></li>
            <li onclick="send('toggleSeen')" style="cursor: pointer;">Seen</li>
            <li onclick="send('pausedAt')" style="cursor: pointer;">Paused at</li>
            <li onclick="send('toggleReWatch')" style="cursor: pointer;">Rewatch</li>
        </ul>
    </div>
        <textarea id="sql" class="adjustableText" id="myInput" placeholder="sql:" style="display:none"></textarea>
        <table>
            <thead>
                <tr style='border-collapse:collapse; text-align:center; position:sticky; top:0; background-color:white;'>
                    <th>Index</th>
                    <th>Seen</th>
                    <th>Paused at</th>
                    <th>ReWatch</th>
                    <th>Length</th>
                    <th>Description</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                /**  @param Video[] $playList */
                foreach($playList as $video): 
                    /** @var Video $video */
                    $index = $video->getIndex();
                    // $row = get_object_vars($video);?>
                    <tr id="row_<?php echo $index; ?>">
                        <td> <?php echo $index;?> </td>
                        <td id="seen_<?php echo $index; ?>"> <?php echo $video->getSeen();?> </td>
                        <td id="pausedAt_<?php echo $index; ?>"> <?php echo $video->getPausedAt();?> </td>
                        <td id="reWatch_<?php echo $index; ?>"> <?php echo $video->getReWatch() == 1 ? 1 : null ?> </td>
                        <td id="length_<?php echo $index; ?>"> <?php 
                            $seconds =(int) $video->getLength();
                            $minuets = intdiv($seconds , 60);
                            $remainingSeconds = $seconds % 60;
                            $minuetsString = $minuets <10 ? '0' .$minuets : $minuets;
                            $secondsString = $remainingSeconds <10 ? '0' .$remainingSeconds : $remainingSeconds;
                            echo $minuetsString . ":" . $secondsString;
                            ?></td>
                        <?php if (!($playSingleVidoe = true)){ ?>
                            <td style=" text-align: left; cursor: pointer;"><a href = '<?php echo $video->getLink() ?>' target="_blank" style=" text-decoration: none;color: inherit;"> <?php echo $video->getTitle() ?> </a></td>
                        <?php }else{ 
                            // $g_h_outer_root = \INCLUDE\$g_h_outer_root;
                            ?> 
                            <!-- <td style=" text-align: left; cursor: pointer;" style=" text-decoration: none;color: inherit;" 
                                onclick="window.open('< ?php echo $g_h_outer_root . "../103_gio/views/show_youtube.php?videoId=" . $video->'videoId'] ?>', '_blank');">< ?php echo $video->'description'] ?></td> -->
                            <td style=" text-align: left; cursor: pointer;" style=" text-decoration: none;color: inherit;" 
                                onclick="window.open('<?php 
                                    $gHProjectsInclude = new G_h_projects_include(host:'localhost');
                                    echo G_h_projects_include::$g_h_outer_root . "../103_gio/views/show_youtube.php?videoId=" . $video->getVideoId() ?>', '_blank');"><?php echo $video->getTitle() ?></td>
                        <?php } ?>
                    </tr>
                <?php endforeach ?>
            </tbody>
        </table>
    </body>
    </html>
    <script>
    var contextMenu = document.getElementById("context-menu");
    document.addEventListener("keypress", function(event) {
        if (event.keyCode === 115 && document.activeElement.id !== "sql") {// 's'
            get("sql").toggle();
        }
    });
    document.getElementById("sql").addEventListener("keydown", function(event) {
        if (event.key === "Enter") {
            // Enter key pressed in the textarea
            console.log("Enter key pressed! Do something...");
            // Add your code here to perform the desired action
        }
    });
    document.addEventListener("contextmenu", function(e) {
        e.preventDefault();
        var clickedElement = e.target;
        var tr_element = clickedElement.closest("tr");
        if (tr_element) {
            var string = tr_element.id;
            var matches = string.match(/row_(\d+)/);
            if (matches) {
                var index = parseInt(matches[1], 10);
                contextMenu.style.display = "block";
                contextMenu.style.left = e.pageX + "px";
                contextMenu.style.top = e.pageY + "px";
                get("context-menu-title").html(index);
            };
        };
    });
    document.addEventListener("click", function(e) {
        contextMenu.style.display = "none";
    });
    function send(action){
        var row=get("context-menu-title").html();
        var element;
        switch(action){
            case 'toggleSeen':
                element = "seen_"+row;
                break;
            case 'toggleReWatch':
                element = "reWatch_"+row;
                break;
            case 'pausedAt':
                element = "pausedAt_"+row;
                break;
        }
        var value=get(element).html();
        if ((action === 'toggleSeen')||(action === 'toggleReWatch')){
            if (value == 1){
                value = 0;
            }else{
                value = 1;
            };
        }else{
            value = prompt("Set paused at");
        }
        var playlistId = getPlaylistIdFromURI();
        <?php
            // $fetch = new Fetch(class: DoSql::class,method: 'doSql',echoReturn: false);
            $fetch = new Fetch(echoReturn: false);
            $fetch->addMethodArgument('row','row',variable:true);
            $fetch->addMethodArgument('action','action',variable:true);
            $fetch->addMethodArgument('value','value',variable:true);
            $fetch->addMethodArgument('playlistId','playlistId',variable:true);
            $fetch->addThen(function(){
                ?><script>
                switch(action){
                    case 'toggleSeen':
                        get("seen_"+row).html(value);
                        console.log('Record updated successfully.');
                        break;
                    case 'toggleReWatch':
                        get("reWatch_"+row).html(value);
                        console.log('Record updated successfully.');
                        break;
                    case 'pausedAt':
                        get("pausedAt_"+row).html(value);
                        console.log('Record updated successfully.');
                        break;

                }
                </script><?php
            });
            $fetch->catch = function(){
                ?><script>
                    throw new Error('Error updating record.');
                </script>
            <?php };
            $fetch->fetch();
        ?>
    }
    function pausedAt(){
        var row=get("context-menu-title").html();
        var value = prompt("Set paused at");
        var sql="UPDATE playlist SET paused_at = '" + value + "' WHERE `index` = " + row;
        <?php
            $fetch = new Fetch(class: DoSql::class,method: 'doSql',echoReturn: false);
            $fetch->addMethodArgument('sql','sql',variable:true);
            $fetch->addThen(function(){
                ?><script>
                get("pausedAt_"+row).html(value);
                console.log('Record updated successfully.');
                </script><?php
            });
            $fetch->fetch();
        ?>
    }
    function getPlaylistIdFromURI() {
        var urlParams = new URLSearchParams(window.location.search);
        return urlParams.get('playlist_id');
    }
</script>
<?php
    }
}

