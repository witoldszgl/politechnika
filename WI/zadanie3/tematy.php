<!DOCTYPE html>
<html>
<head>
    <title>Demo - Zadanie 1 - WWW i jzyki skryptowe</title>
    <meta charset="utf-8">
    <meta http-equiv="Cache-Control" content="no-store, no-cache, must-revalidate" />
    <meta http-equiv="Pragma" content="no-cache" />
    <link rel="stylesheet" type="text/css" href="styl.css">
</head>
<body>
    <header>
      <h1>
          Zadanie 3
      </h1>
      <h2>
          Proste forum
      </h2>
    </header>
    <nav>
            <a href="../">Home</a>
            <?php for($n=1;$n<=10;$n++) { if( is_dir("../zadanie".$n) ) { ?>
            <a href="../zadanie<?=$n?>">Zadanie <?=$n?></a>
            <?php } } ?>
    </nav>
<section class="user-info">
    <?php if($_SESSION['adminTrue'])
            { ?> <div><a href="?cmd=userlist">Lista uczestników</a></div> <?php } ?>
    Zalogowany jako: <?=$_SESSION['userid']?> (<?=$_SESSION['userdes']?>) <a href="?cmd=logout" >WYLOGUJ</a>
    <br />
    <?php
        if($_SESSION['adminTrue'] and $_SESSION['showList'])
        {
            ?> <table><tr><th>Identyfikator</th><th>Nazwa</th><th>Poziom</th><th></th></tr> <?php
            $users=getUser();
            foreach($users as $user)
            {
                    ?>
                    <tr><td><?=$user['userid']?></td><td><?=$user['des']?></td><td><?php
                    if($user['auth']){echo "admin";}else{echo "user";} ?></td><td><?php
                    if($user['userid']==$_SESSION['userid'] or $_SESSION['adminTrue'])
                    {
                        if($user['userid']!="admin")
                        {
                            ?>
                            <a href="?cmd=changeuser&userid=<?=$user['userid']?>">Zmie�</a>&nbsp;
                            <a class="danger" href="?cmd=deluser&userid=<?=$user['userid']?>">Kasuj</a>
                            <?php
                        }
                    }
                    ?> </td></tr> <?php
            }
        }
    ?>
    </table>
</section>
<section>
<?php if( !$topics ){ ?>
  <p>To forum nie zawiera jeszcze żadnych tematów!</p>
<?php }else{ ?>
  <p>Możesz dodac nowy temart za pomocą <a href="#topic_form">formularza</a>.</p>
<?php foreach($topics as $k=>$v){ ?>
  <article class="topic">
    <header> </header>
    <div><a href="?topic=<?=$k?>"><?=htmlentities($v['topic'])?></a></div>
    <footer>
    <nav>
    <?php if($_SESSION['userid']==$v['username'] or $_SESSION['adminTrue']){ ?>
        
          <!--<a href="?topic=<?=$_GET['topic']?>&id=<?=$v['postid']?>&cmd=edit">EDYTUJ</a>  -->
          <a class="danger" href="?id=<?=$v['topicid']?>&cmd=deleteTopic">KASUJ</a>
          <?php }
          ?>
    </nav>
    ID: <?=$v['topicid']?>, Autor: <?=htmlentities($v['username'])?>, 
        Utworzono: <?=$v['date']?>, Liczba wpisów: <?=isset($posts_count[$v['topicid']])?$posts_count[$v['topicid']]:0;?>
    </footer>
  </article>
<?php } } ?>
  <form action="index.php" method="post">
     <a name="topic_form"></a>
     <header><h2>Dodaj nowy temat do dyskusji</h2></header>  
     <input type="text" name="topic" placeholder="Nowy temat" autofocus \><br />
     <textarea name="topic_body" cols="80" rows="10" placeholder="Opis nowego tematu" ></textarea><br />
     <!--<input type="text" name="username" placeholder="Imię autora" \><br />-->
     <button type="submit" >Zapisz</button>
  </form>
</section>

<footer>
Ostatni wpis na formu powstał dnia: <?=get_last_post_date($posts_file, $separator);?>
</footer>
</body>
</html>    