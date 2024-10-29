<html>
<head>
    <title>TWWW - User: <?=$username?>, <?=$dirname?></title>
    <meta charset="UTF-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate">
    <meta http-equiv="Pragma" content="no-cache">
    <link href="styl.css" rel="stylesheet">
</head>
<body>
<header>
    <h1><?=$dirname?></h1>
</header>
<nav>
    <a href="<?="/$username"?>" title="Strona pocz¹tkowa serwisu" target="_self">START</a>
    <?php foreach ($tasks as $task) { ?>
        <a href="<?="/$username/$task"?>/" title="<?=$task?>" target="_self"><?=$task?></a>
    <?php } ?>
</nav>
<section>
    <nav><table><tr><td>
    <?php
        if ($topicid > 1)
        {
          echo '<a style="float:left;" href="index.php?topic='.($topicid-1).'"><-- Poprzedni temat</a>';
        }
        echo '</td><td style="width: 33%;"><a href="index.php">Lista tematów</a></td><td style="width: 33%;">';
        if ($topicid < $count)
        {
          echo '<a style="float:right;" href="index.php?topic='.($topicid+1).'">Nastêpny temat --></a></td>';
        }
        echo '</tr></table></nav>';
        $topics=get_topic();
        $topic=array();
        foreach ($topics as $tmptopic)
        {
            if ($tmptopic['topicid'] == $topicid)
            {
                $topic = $tmptopic;
                break;
            }
        }
        ?>
        <article  class="topic">
            <header>Temat dyskusji: <b><?=$topic['topic']?></b></header>
            <div><?=$topic['topic_body']?></div>
            <footer>
            ID: <?=$topic['topicid']?>, Autor: <?=$topic['username']?>, Data: <?=$topic['date']?>    </footer>
        </article>Wpisy:
        <?php
            $posts = get_posts($topicid);
            foreach($posts as $post)
                {
                    echo "<article>"."<div>".$post['post']."</div>";
                    echo "<footer>"."<nav>".'<a href="?topic='.$topicid.'&id='.$post['postid'].'&cmd=edit">EDYTUJ</a>';
                    echo '<a class="danger" href="?topic='.$topicid.'&id='.$post['postid'].'&cmd=delete">KASUJ</a>'."</nav>";
                    echo "ID: " . $post['postid'] . ", Autor: " . $post['username'] . ", Utworzono dnia: " . $post['date'];
                    echo "</footer>"."</article>";
                }
        
        {
            $postTMP=array();
            foreach($posts as $post)
            {
                if($_GET['id']==$post['postid'])
                {
                    $postTMP=$post;
                }
            }
            ?>
            <section>
                <form action="index.php?topic=<?=$topicid?>" method="post">
                <a name="post_form" ></a>
                <header><h2>Edycja wypowiedzi</h2></header>  
                <textarea name="post" autofocus cols="80" rows="10" placeholder="Wpisz tu swoj¹ wypowiedŸ."><?=$postTMP['post']?></textarea><br />
                <input type="text" name="username" placeholder="Imiê autora" value="<?=$postTMP['username']?>"\><br />
                <input type="hidden" name="postid" value="<?php couterofpost($topicid)+1 ?>" />
                <button type="submit" >Zapisz</button>
                </form>
                <?php
                    if (isset($_POST["post"]) && isset($_POST["username"]) && isset($_POST["postid"]))
                    {
                        editLineInFile($_POST['postid'], $_POST['post'], $_POST['username']);
                        header("Location: index.php?topic=$topicid");
                    }
                ?>

            </section><?php
        }?>
</section>