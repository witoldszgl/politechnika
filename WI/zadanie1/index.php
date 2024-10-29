<!--written by Rusin Krystian-->
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
?>
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
    <a href="<?="/$username"?>" title="Strona początkowa serwisu" target="_self">Strona początkowa serwisu</a>
    <?php foreach ($tasks as $task) { ?>
        <a href="<?="/$username/$task"?>/" title="<?=$task?>" target="_self"><?=$task?></a>
    <?php } ?>
</nav>
<section>
    <?php
    $counter=0;
    $tmpData="";
    $file = fopen("dane.txt", "r") or die("Nie można otworzyć pliku!");
    while (!feof($file)) {
        ?><article><?php
        $line = fgets($file);
        if ($line != "")
        {
            ?>
            <header><h2><?php echo $line; $counter++; ?></h2></header><div><?php
        }
        else
        {
            break;
        }
        while (true)
        {
            $line = fgets($file);
            if (substr($line, 0, 6) == "Data: ")
            {
                ?>
                </div><footer><p><?php echo $line."<br>\n";?></p></footer><?php
                if($tmpData=="")
                {
                    $tmpData=substr($line, 6, 19);
                }
                break;
            }
            else
            {
                echo $line."<br>\n";
            }
            if ($line == "")
            {
                break;
            }
        }
        ?></div></article><?php
    }
fclose($file);
?>
</section>
<footer>Stopka wiadomości: TWWW - User: <?=$username?></footer>

<section>
    <aside>
        <p>Ostatni wpis: <?=$tmpData?></p>
        <p>Liczba wpisów: <?=$counter?></p>
    </aside>
    <form action="" method="post">
        <header><h2>Wypełnij i zapisz</h2></header>
        <input type="text" name="title" placeholder="Tytuł wiadomości" autofocus><br/>
        <textarea name="body" cols="80" rows="10" placeholder="Treść wiadomości"></textarea><br/>
        <button type="submit">Zapisz</button>
    </form>
    <?php
    if (isset($_POST["title"]) && isset($_POST["body"])) {
        $data1 = $_POST["title"];
        $data2 = $_POST["body"];
        $fp = fopen("dane.txt", 'a');
        fwrite($fp, "\n".$data1."\n");
        fwrite($fp, $data2."\n");
        fwrite($fp, "Data: ".date("Y-m-d, H:i:s").", IP: ".$_SERVER['HTTP_X_FORWARDED_FOR']);
        fclose($fp);
        header("Location: index.php");
    }
    ?>
</section>
<footer>
    <p>Stopka dokumentu: TWWW - IR - WIiT PP
