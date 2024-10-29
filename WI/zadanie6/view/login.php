<section id="login">
    <form action="<?=$this->baseurl;?>" method="post">
     <a name="login_form"></a>
     <header><h2>Zaloguj się do forum</h2></header>  
     <input type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
     <input type="password" name="pass" placeholder="Hasło" \><br />
     <?php if (isset($error1)) { ?>
        <div class="error"><?php echo $error1; ?></div>
     <?php } ?>
     <button type="submit" >Zaloguj się</button>
  </form>
</section>    
