<?php
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
session_start();
if (!isset($_SESSION['errortxt'])) {$_SESSION['errortxt'] = null;}
if (!isset($_SESSION['userid'])) {$_SESSION['userid'] = null;}
if (!isset($_SESSION['userdes'])) {$_SESSION['userdes'] = null;}
if (!isset($_SESSION['showList'])) {$_SESSION['showList'] = false;}
$_SESSION['adminTrue']=false;
include("function.php");

// Konfiguracja
$posts_file = 'wypowiedzi1.txt';
$topic_file = 'tematy1.txt';
$user_file = "users.txt";
$separator = ":-:";

if( !is_file($posts_file) ) file_put_contents($posts_file,'');
if( !is_file($topic_file) ) file_put_contents($topic_file,'');

//sprawdŸ czy admin
if($_SESSION['userid'])
{
    if(checkAdmin($_SESSION['userid']))
        $_SESSION['adminTrue']=true;
}

// zapis tematu
if( isset($_POST['topic']) and $_POST['topic']!="" and $_POST['topic_body']!="")
{
  $res = put_topic($_POST['topic'], $_POST['topic_body'], $_SESSION['userid'], $topic_file, $separator);
  header("Location: index.php");exit;
}   

// zapis lub aktualizacjia postu
if( isset($_POST['post']) and $_POST['post']!="")
{
  if( $_POST['postid']!='' )
  {
     $res = update_post( $_POST['postid'], $_POST['post'], $_SESSION['userid'],  $posts_file, $separator );
  }
  else
  {
     $res = put_post( $_GET['topic'], $_POST['post'], $_SESSION['userid'], $posts_file, $separator);
  }
  header("Location: index.php?topic=".$_GET['topic'] );exit;
}   

// kasowanie postu
if( isset($_GET['cmd']) and $_GET['cmd']=="delete" and $_GET['id']!="" and $_GET['topic']!="")
{
  delete_post($_GET['id'], $posts_file, $separator);
  header("Location: index.php?topic=".$_GET['topic'] );exit;
}

// pobranie danych postu w celu ich edycji
if( isset($_GET['cmd']) and $_GET['cmd']=="edit" and $_GET['id']!="" and $_GET['topic']!="")
{
  $post = get_post($_GET['id'], $posts_file, $separator);
}
else
{
  $post=false;
}  

// Pobranie wszystkicj tematów
$topics = get_topics($topic_file, $separator);

// kasowanie tematu
if( isset($_GET['cmd']) and $_GET['cmd']=="deleteTopic" and $_GET['id']!="")
{
    delete_posts_by_topicid($_GET['id'],$posts_file,$separator);
    delete_topic($_GET['id'],$topic_file,$separator);
    header("Location: index.php");
}

// Nowy u¿ytkownik
if(isset($_POST['username']) and $_POST['userid']!='' and $_POST['pass1']!='' and $_POST['pass2']!='')
{
    if($_POST['pass1'] == $_POST['pass2'])
    {
        $users=getUser();
        $tmpNoUser=false;
        foreach($users as $user)
        {
            if($_POST['userid'] == $user['userid'])
            {
                $tmpNoUser=true;
                break;
            }
        }
        if(!$tmpNoUser)
        {
            setUserToDB($_POST['userid'],$_POST['username'],$_POST['pass1'],$user_file,$separator);
            $_SESSION['userid']=$_POST['userid'];
            header("Location: index.php");
        }
    }
}

//-------------------------------------------------------------
// Prezentacja
//-------------------------------------------------------------

if($_SESSION['userid'])
{
    if( isset($_GET["topic"]) and $_GET["topic"]!='' )
    {  
       $posts = get_posts($_GET["topic"], $posts_file, $separator);
       $topic= $topics[$_GET["topic"]];
       include('wypowiedzi.php');
    }
    else
    {
         $posts_count = get_posts_count($posts_file, $separator);
         include('tematy.php'); 
    }
    if(isset($_GET['cmd']))
    {
        if($_GET['cmd']=="logout")
        {
            $_SESSION = array();
            if (ini_get("session.use_cookies"))
            {
                $params = session_get_cookie_params();
                setcookie(session_name(), '', time() - 42000,
                    $params["path"], $params["domain"],
                    $params["secure"], $params["httponly"]
                    );
            }
            session_destroy();
            header("Location: index.php");
        }
        if($_GET['cmd'])
        {
            if($_GET['cmd']=="changeuser")
            {
                updateUserAuth($_GET['userid']);
                header("Location: index.php");
            }
            else if($_GET['cmd']=="deluser")
            {
                deleteUser($_GET['userid']);
                header("Location: index.php");
            }
            else if($_GET['cmd']=="userlist")
            {
                if($_SESSION['showList'])
                {
                    $_SESSION['showList']=false;
                    header("Location: index.php");
                }
                else
                {
                    $_SESSION['showList']=true;
                    header("Location: index.php");
                }
            }
        }
    }
}
else
{
    include('main.php');
}
?>