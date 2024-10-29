<section style="padding-bottom: 20px" class="user-info">
    <?php if($this->u['userlevel']==10){ ?>
        <div><a href="?cmd=imagelist">Lista obrazów</a></div>
    <?php } ?>
    <?php if($_SESSION['imagelist']){ ?>
        <br />
        <table><tr><th>Przesłano</th><th>Właściciel</th><th>Identyfikator</th><th>Tytuł</th><th>Opis</th><th>Grafika</th><th>Opcje</th></tr>
            <?php foreach($images as $k=>$v){ ?>
                <tr>
                    <form action="?id=<?=$v['post_id']?>&cmd=upload-image" method="post" enctype="multipart/form-data">
                    <td>
                        <?=$v["date"]?>
                    </td>
                    <td>
                        <?=$v["userid"]?>
                    </td>
                    <td><?=$v['imageid']?>
                        <?php if (isset($_GET["cmd"]) and $_GET['cmd'] == "edit-image" and $_GET['imageid'] == $v["imageid"]) { ?>
                            <input type="hidden" name="imageid" value="<?=$v["imageid"]?>" />
                        <?php } ?></td>
                    <td><?=$v['title']?>
                        <?php if (isset($_GET["cmd"]) and $_GET['cmd'] == "edit-image" and $_GET['imageid'] == $v["imageid"]) { ?>
                            <input style="width:auto" type="text" name="image-title" placeholder="Wpisz tytuł" value="<?=$v["title"];?>">
                        <?php } ?></td>
                    <td><?=$v['description']?>
                        <?php if (isset($_GET["cmd"]) and $_GET['cmd'] == "edit-image" and $_GET['imageid'] == $v["imageid"]) { ?>
                            <input style="width:auto" type="text" name="image-description" placeholder="Wpisz opis" value="<?=$v["description"];?>">
                        <?php } ?></td>
                    <td><img style="width: 100px;height: 100px" src="<?=$v["image_file"]?>">
                        <?php if (isset($_GET["cmd"]) and $_GET['cmd'] == "edit-image" and $_GET['imageid'] == $v["imageid"]) { ?>
                            <input style="width:auto" type="file" name="fileToUpload" id="fileToUpload">
                        <?php } ?>
                    </td>
                    <td>
                        <?php if (isset($_GET["cmd"]) and $_GET['cmd'] == "edit-image" and $_GET['imageid'] == $v["imageid"]) { ?>
                            <input style="width:auto" type="submit" value="ZAKTUALIZUJ OBRAZ" name="submit">
                        <?php } else { ?>
                            <a href="?id=<?=$v['post_id']?>&cmd=edit-image&imageid=<?=$v['imageid']?>">EDYTUJ OBRAZEK</a>
                            <a class="danger" href="?id=<?=$v['post_id']?>&cmd=delete-image&imageid=<?=$v['imageid']?>">KASUJ OBRAZEK</a>
                        <?php } ?></td>
                    </form>
                </tr>
            <?php } ?>
        </table>
    <?php } ?>
</section>