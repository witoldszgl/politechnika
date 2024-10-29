<!--written by Rusin Krystian-->
<?php
include("function.php");
$path = explode('/', dirname(__FILE__));
$dirname = array_pop($path);
$username = array_pop($path);
$path = implode('/', $path);
$tasks = array();
foreach (scandir("$path/$username") as $dir) {
    if (is_dir("$path/$username/$dir") && $dir != '.' && $dir != '..') {
        $tasks[] = $dir;
    }
}
if(isset($_GET['topic']))
{
    $topicid = $_GET['topic'];
    $count = couteroftopic();
    $test=False;
    if(isset($_GET['cmd']))
    {
        if($_GET['cmd']=="delete")
        {
            $lines = file('wypowiedzi.txt');
            for($i=0; $i<count($lines); $i++)
            {
                $record = explode(":-:", trim($lines[$i]));
                if($record[0] == $_GET['id'])
                {
                    unset($lines[$i]);
                    break;
                }
            }
            file_put_contents('wypowiedzi.txt', implode('', $lines));
            header("Location: index.php?topic=$topicid");
        }
        else if($_GET['cmd']=="edit")
        {
            $test=True;
        }
    }
    if($test) include("post_edit.php"); else include("post.php");
    
}
else
{
    include("topic.php");
}
?>