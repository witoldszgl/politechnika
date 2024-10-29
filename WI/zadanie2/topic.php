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
    <?php $topics = get_topic();
    foreach ($topics as $topic) 
    { ?>
        <article class="topic">
        <header></header>
        <div><a href="?topic=<?=$topic['topicid']?>"> <?=$topic['topic']?></a></div>
        <footer>ID: <?=$topic['topicid']?>, Autor: <?=$topic['username']?>, Utworzono: <?=$topic['date']?></footer>
        </article> <?php
    } ?>
</section>
<section>
    <form action="" method="post">
        <header><h2>Dodaj nowy temat</h2></header>
        <input type="text" name="topic" placeholder="Nowy temat" autofocus><br/>
        <textarea name="topic_body" cols="80" rows="10" placeholder="Opis nowego tematu"></textarea><br/>
        <input type="text" name="username" placeholder="Autor tematu" autofocus><br/>
        <button type="submit">Zapisz</button>
    </form>
    <?php
        if (isset($_POST["topic"]) && isset($_POST["topic_body"]) && isset($_POST["username"]))
        {
            put_topic($_POST["topic"],$_POST["topic_body"],$_POST["username"]);
            header("Location: index.php");
        }
    ?>
</section>
<footer><p>Stopka dokumentu: TWWW - IR - WIiT PP