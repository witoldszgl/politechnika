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
<section id="login">
    <form action="index.php" method="post" name="form-login">
        <a name="login_form"></a>
        <header><h2>Zaloguj się do forum</h2></header>  
        <input type="text" name="userid" placeholder="Nazwa logowania" pattern="[A-Za-z0-9\-]*" autofocus \><br />
        <input type="password" name="pass" placeholder="Hasło" \><br/>
        <input type="hidden" name="form-login" value="form-login">
        <div class="error"><?=$_SESSION['errortxt']?></div>
        <button type="submit" >Zaloguj się</button>
    </form>
    <form action="index.php" method="post" name="newuser_form">
        <header><h2>Jesli nie jesteś zarejestrowany, to możesz zapisać się do forum.</h2></header>  
        <input type="text" name="userid" placeholder="Nazwa logowania (dozwolone są tylko: litery, cyfry i znak '-')" pattern="[A-Za-z0-9\-]*" autofocus \><br />
        <input type="text" name="username" placeholder="Imię autora" \><br />
        <input type="password" name="pass1" placeholder="Hasło" \><br />
        <input type="password" name="pass2" placeholder="Powtórz hasło" \><br />
        <button type="submit" >Zapisz się do forum</button>
    </form>
</section>  
<footer><p>Stopka dokumentu: TWWW - IR - WIiT PP
</body>
</html>