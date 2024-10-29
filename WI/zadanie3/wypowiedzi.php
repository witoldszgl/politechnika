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
<section>
  <nav>
    <table><tr>
    <td>
<?php if( $p=get_previous_topic_id($_GET['topic'], $topic_file, $separator) ){ ?>
    <a style="float:left;" href="index.php?topic=<?=$p?>">&lt;-- Poprzedni temat</a>
<?php } ?>
    </td><td  style="width: 33%;">
    <a href="index.php">Lista tematów</a>
    </td><td  style="width: 33%;">  
<?php if( $p=get_next_topic_id($_GET['topic'], $topic_file, $separator) ){ ?>
    <a  style="float:right;" href="index.php?topic=<?=$p?>">Następny temat --&gt;</a>
<?php } ?>
    </td>
    </tr></table>
  </nav>
  <article  class="topic">
    <header>Temat dyskusji: <b><?=htmlentities($topic['topic'])?></b></header>
    <div><?=nl2br(htmlentities($topic['topic_body']))?></div>
    <footer>
    ID: <?=$topic['topicid']?>, Autor: <?=htmlentities($topic['username'])?>, Data: <?=$topic['date']?>
    </footer>
  </article>
<?php if( !$posts ){ ?>
  <p>To forum nie zawiera jeszcze żadnych głosów w dyskusji!</p>
  <p>Możesz dodać nową wypowiedź za pomocą <a href="#post_form">formularza</a>.</p>
<?php }else{ ?>
  <p>Możesz dodać nową wypowiedź za pomocą <a href="#post_form">formularza</a>.</p>
<?php foreach($posts as $k=>$v)
      { ?>
          <article>
          <div><?=nl2br(htmlentities($v['post']))?></div>
          <footer>
          <nav>
          <?php if($_SESSION['userid']==$v['username'] or $_SESSION['adminTrue']){ ?>
        
          <a href="?topic=<?=$_GET['topic']?>&id=<?=$v['postid']?>&cmd=edit">EDYTUJ</a>  
          <a class="danger" href="?topic=<?=$_GET['topic']?>&id=<?=$v['postid']?>&cmd=delete">KASUJ</a>
          <?php }
          ?>
          </nav> 
          ID: <?=$v['postid']?>, Autor: <?=htmlentities($v['username'])?>, Utworzono dnia: <?=$v['date']?></footer>
          </article>
          <?php 
      }
 } ?>
  <form action="index.php?topic=<?=$_GET['topic']?>" method="post">
     <a name="post_form" ></a>
     <header><h2><?php if($post){ ?>Edytuj wypowiedź<?php }else{ ?>Dodaj nowa wypowiedź do dyskusji<?php } ?></h2></header>  
     <textarea name="post" autofocus cols="80" rows="10" placeholder="Wpisz tu swoją wypowiedź." ><?=($post)?$post["post"]:'';?></textarea><br />
     <!--<input type="text" name="username" placeholder="Imię autora" value="<?=($post)?$post["username"]:'';?>"\><br />-->
     <input type="hidden" name="postid" value="<?=($post)?$post["postid"]:"";?>" />
     <button type="submit" >Zapisz</button>
  </form>
</section>
<footer>
Ostatni wpis na formu powstał dnia: <?=get_last_post_date($posts_file, $separator);?>
</footer>
</body>
</html>        