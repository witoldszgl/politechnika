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
    <a href="<?="/$username"?>" title="Strona początkowa serwisu" target="_self">START</a>
    <?php foreach ($tasks as $task) { ?>
        <a href="<?="/$username/$task"?>/" title="<?=$task?>" target="_self"><?=$task?></a>
    <?php } ?>
</nav>
<section>
    <nav><table><tr><td>
    <?php
        if ($topicid > 1)
        {
            ?><a style="float:left;" href="index.php?topic=<?=$topicid-1?>"><-- Poprzedni temat</a><?php
        }
            ?></td><td style="width: 33%;"><a href="index.php">Lista temat�w</a></td><td style="width: 33%;"><?php
        if ($topicid < $count)
        {
            ?><a style="float:right;" href="index.php?topic=<?=$topicid+1?>">Nast�pny temat --></a></td><?php
        }
        ?></tr></table></nav><?php
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
            ?>
            <section>
                <form action="index.php?topic=<?=$topicid?>" method="post">
                <a name="post_form" ></a>
                <header><h2>Dodaj nowa wypowied� do dyskusji</h2></header>  
                <textarea name="post" autofocus cols="80" rows="10" placeholder="Wpisz tu swoj� wypowied�." ></textarea><br />
                <input type="text" name="username" placeholder="Imi� autora" value=""\><br />
                <input type="hidden" name="postid" value="<?php couterofpost($topicid)+1 ?>" />
                <button type="submit" >Zapisz</button>
                </form>
                <?php
                if (isset($_POST["post"]) && isset($_POST["username"])){
                    put_post($topicid,$_POST["post"],$_POST["username"]);
                    header("Location: index.php?topic=$topicid");
                }
                ?>
            </section><?php
        }?>
</section>