<?php 
   if( !isset($error1) ) $error1 = "";
   if( !isset($error2) ) $error2 = ""; 
?>
<nav class="menu">
   <?php
      if(!isset($_SESSION['TryLogin'])) $_SESSION['TryLogin']=True;
      if(!isset($_SESSION['TryReg'])) $_SESSION['TryReg']=True;
      if($_SESSION['TryLogin']) { ?><a href="?cmd=register">Rejestracja</a><?php }
      if($_SESSION['TryReg']) {?><a href="?cmd=login">Logowanie</a><?php } ?>
</nav>
<?php if($_SESSION['TryLogin']) { ?>
   <section id="login">
      <form action="<?=$this->baseurl;?>" method="post">
      <a name="login_form"></a>
      <header><h2>Zaloguj się do forum</h2></header>  
      <input type="text" name="useridL" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
      <input type="password" name="pass" placeholder="Hasło" \><br />
      <?="<div class=\"error\">$error1</div>";?>
      <button type="submit" >Zaloguj się</button>
   </form>
   </section>
<?php } else {?>
   <section id="register">
      <form action="<?=$this->baseurl;?>" method="post">
      <a name="newuser_form"></a>
      <header><h2>Jesli nie jesteś zarejestrowany, to możesz zapisać się do forum.</h2></header>  
      <input type="text" name="useridR" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
      <input type="text" name="username" placeholder="Imię autora" \><br />
      <input type="password" name="pass1" placeholder="Hasło" \><br />
      <input type="password" name="pass2" placeholder="Powtórz hasło" \><br />
      <div style="text-align:center;margin:10px 0;">
         <img src="?capthaimg=1" alt="captcha" title="Wpisz kod kontrolny z obrazka" onclick="this.src='?capthaimg='+Math.random();">
         </br>'kliknij' na obrazek by zmieńić kod kontrolny</br>
         <input style="width:300px;" type="text" name="captcha" placeholder="Wpisz kod kontrolny z obrazka" \>
      </div>
      <?="<div class=\"error\">$error2</div>";?>
      <button type="submit" >Zapisz się do forum</button>
      </form>
   </section>
<?php } ?>