<?php
function getUser()
{
    $separator=":-:";
    if ($data = file('users.txt'))
    {
        $users = array();
        foreach ($data as $line)
        {
            $record = explode($separator, trim($line));
            {
                $user = array(
                    "userid" => $record[0],
                    "des" => $record[1],
                    "passMD5" => $record[2],
                    "auth" => $record[3]
                );
                $users[] = $user;
            }
        }
        return $users;
    } else {
        return FALSE;
    }
}
function tryLogin($userid, $pass)
{
    $users=getUser();
    foreach($users as $user)
    {
        if($user['userid'] == $userid and $user['passMD5']==md5($pass))
        {
            return $user['des'];
        }
    }
    return False;
}
function checkAdmin($userid)
{
    $users=getUser();
    foreach($users as $user)
    {
        if($user['userid']==$userid)
            if($user['auth'])
                return true;
    }
    return false;
}
function setUserToDB($userid, $des, $pass,$user_file,$separator)
{
    $data = implode( $separator, 
                     array( $userid, 
                            $des,
                            md5($pass),
                            "0"
                    ));
    if( $fh = fopen( $user_file, "a+" ))
    {
        fwrite($fh, $data."\n");
        fclose($fh);
    }
}
function updateUserAuth($userid)
{
    $users = getUser();
    foreach ($users as &$user)
    {
        if ($user['userid'] == $userid)
        {
            if($user['auth'])
            {
                $user['auth'] = 0;
            }
            else
                $user['auth'] = 1;
        }
    }
    unset($user);

    $separator = ":-:";
    $file_contents = '';
    foreach ($users as $user)
    {
        $file_contents .= implode($separator, array($user['userid'], $user['des'], $user['passMD5'], $user['auth'])) . "\n";
    }

    $user_file = "users.txt";
    if ($fh = fopen($user_file, "w"))
    {
        fwrite($fh, $file_contents);
        fclose($fh);
        return true;
    }
    else
    {
        return false;
    }
}
function deleteUser($userid)
{
    $separator = ":-:";
    $user_file = "users.txt";
    $users = getUser();
    $deleted = false;
    foreach ($users as $key => $user)
    {
        if ($user['userid'] == $userid)
        {
            unset($users[$key]);
            $deleted = true;
            break;
        }
    }
    if ($deleted)
    {
        $fh = fopen($user_file, "w");
        foreach ($users as $user)
        {
            $data = implode($separator, array($user['userid'], $user['des'], $user['passMD5'], $user['auth']));
            fwrite($fh, $data . "\n");
        }
        fclose($fh);
    }
}
function delete_posts_by_topicid($topicid, $datafile, $separator=":-:")
{
    $data = file($datafile);
    $new_data = array();
    foreach ($data as $line)
    {
        $record = explode($separator, trim($line));
        if ($record[1] != $topicid)
        {
            $new_data[] = $line;
        }
    }
    if ($fh = fopen($datafile, "w"))
    {
        fwrite($fh, implode("", $new_data));
        fclose($fh);
        return true;
    } else {
        return false;
    }
}
function delete_topic($topicid, $datafile, $separator=":-:")
{
    if ($data = file($datafile))
    {
        $newdata = array();
        foreach ($data as $line)
        {
            $record = explode($separator, trim($line));
            if ($record[0] != $topicid)
            {
                $newdata[] = $line;
            }
        }
        if (count($newdata) != count($data))
        {
            $newdata = implode("", $newdata);
            if ($fh = fopen($datafile, "w"))
            {
                fwrite($fh, $newdata);
                fclose($fh);
                return true;
            }
        }
    }
    return false;
}
if(isset($_POST['userid']) and isset($_POST['pass']) and $_POST['form-login']=="form-login")
{
    $_SESSION['userdes']=tryLogin($_POST['userid'], $_POST['pass']);
    if($_SESSION['userdes'])
    {
        $_SESSION['errortxt']="";
        $_SESSION['userid'] = $_POST['userid'];
        header("Location: index.php");
    }
    else
    {   
        $_SESSION['errortxt']="Błędny identyfikator lub hasło!";
        header("Location: index.php");
    }
}
?>
<?php
// ---------------------------------------------------------------------------
// Topics - funkcje zarzadzania tematami
//------------------------------------------------------------------------------
// funkcja zapisu do pliku
function put_topic($topic, $topic_body, $username, 
                   $datafile="tematy.txt", $separator=":-:" )
{
   // ostatni wiersz zawiera najm�odszy wpis
   if( is_file($datafile) ){
      // odczyt pliku
      $data=file( $datafile );
      // pobranie danych z ostatniego elementu tablicy $data
      $record = explode( $separator, trim(array_pop($data))); 
      $id = (count($record)>1)?($record[0] + 1):1;
   }else{
      $id = 1;    
   }
   // utworzenie nowego wiersz danych
   // zakodowanie przez bin2hex() danych przes�anych przez u�tykownika
   $data = implode( $separator, 
                     array( $id, 
                            bin2hex($topic),
                            bin2hex($topic_body), 
                            bin2hex($username), 
                            date("Y-m-d H:i:s") 
                  ));
   // zapis danych na ko�cu pliku
   if( $fh = fopen( $datafile, "a+" )){
      fwrite($fh, $data."\n");
      fclose($fh);
      return $postid;
   }else{
      return FALSE;
   };                               
}

//------------------------------------------------------------------------------
// funkcja odczytu z pliku wszystkich temat�w
function get_topics( $datafile="tematy.txt", $separator=":-:" )
{
   // wczytanie pliku do tablicy string�w
   if( $data=file( $datafile ) ){
      // utworzenie pustej tablicy wynikowej
      $topics=array();
      // dla ka�dego elementu tablicy $data
      //    $k - klucz ementu,  $v - warto�� elementu
      foreach($data as $k=>$v){
          // umieszcza kolejne elementy wiersza rozdzielone separatoerm 
          // w kolejnych elementach zwracanej tablicy
          $record = explode( $separator, trim($v));
          // jesli pasuje identyfikator tematu
          // przepakowanie do $posts[] i dekodowanie danych u�ytkownika
          $topics[$record[0]]=array( 
             "topicid"    => $record[0],
             "topic"      => hex2bin($record[1]),
             "topic_body" => hex2bin($record[2]),
             "username"   => hex2bin($record[3]),
             "date"       => $record[4]
          );
      }
      // zwraca tablice z wynikami
      return $topics;   
   }else{
      // zwraca kod b��du
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja wyznacza id poprzedniego tematu
function get_previous_topic_id( $topicid, 
                                $datafile="tematy.txt", $separator=":-:")
{
    $data=file( $datafile );
    $pre=0;
    if( count($data) ){
       foreach($data as $k=>$v ){
          $r = explode( $separator, trim($v));
          if( $r[0]<$topicid) $pre=$r[0];
          if( $r[0]==$topicid ) break;  
       }
    }
    return $pre;
}

//------------------------------------------------------------------------------
// funkcja wyznacza id nast�pnego tematu
function get_next_topic_id( $topicid, 
                            $datafile="tematy.txt", $separator=":-:")
{
    $data=file( $datafile );
    $next=0;
    if( count($data) ){
       foreach($data as $k=>$v ){
          $r = explode( $separator, trim($v));
          if( $r[0]<$topicid ) continue;
          if( $r[0]>$topicid) {
             $next=$r[0];
             break;
          }     
       }
    }
    return $next;
}

// ---------------------------------------------------------------------------
// Posts - funkcje zarzadzania wypowiedziami
//------------------------------------------------------------------------------
// funkcja wyszukuj�ca wypowiedzi na okre�lony temat
//   $topicid - identyfikator tematu
//   $datafile - �cie�ka do pliku zawieraj�cego dane
//   $separator - znaki tworz�ce separator p�l rekordu
//
// format pliku danych:
// postid:-:topicid:-:post:-:username:-:date
// 
function get_posts( $topicid, 
                    $datafile="wypowiedzi.txt", $separator=":-:")
{
   // wczytanie pliku do tablicy string�w
   if( $data=file( $datafile ) ){
      // utworzenie pustej tablicy wynikowej
      $posts=array();
      // dla ka�dego elementu tablicy $data
      //    $k - klucz ementu,  $v - warto�� elementu
      foreach($data as $k=>$v){
          // umieszcza kolejne elementy wiersza rozdzielone separatoerm 
          // w kolejnych elementach zwracanej tablicy
          $record = explode( $separator, trim($v));
          // jesli pasuje identyfikator tematu
          if( $record[1]==$topicid ){
              // przepakowanie do $posts[] i dekodowanie danych u�ytkownika
              $posts[]=array( 
                 "postid"  => $record[0],
                 "topicid" => $record[1],
                 "post"    => hex2bin($record[2]),
                 "username"=> hex2bin($record[3]),
                 "date"    => $record[4]
              );
          }
      }
      // zwraca tablice z wynikami
      return $posts;   
   }else{
      // zwraca kod b��du
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja zapisu wypowiedzi do pliku
function put_post( $topicid, $post, $username, 
                   $datafile="wypowiedzi.txt", $separator=":-:")
{
   // ostatni wiersz zawiera najm�odszy wpis
   if( is_file($datafile) ){
      // odczyt pliku
      $data=file( $datafile );
      $postid = 1;
      // pobranie danych z ostatniego elementu tablicy $data
      if( $last = trim(array_pop($data)) ){
         $record = explode( $separator, $last); 
         $postid = $record[0]+1;
      }
   }      
   // utworzenie nowego wiersz danych
   // zakodowanie przez bin2hex() danych przes�anych przez u�tykownika
   $data = implode( $separator, 
                     array( $postid, 
                            $topicid, 
                            bin2hex($post), 
                            bin2hex($username), 
                            date("Y-m-d H:i:s") 
                     )
                  );
   // zapis danych na ko�cu pliku
   if( $fh = fopen( $datafile, "a+" )){
      fwrite($fh, $data."\n");
      fclose($fh);
      return $postid;
   }else{
      return FALSE;
   };                               
}

//------------------------------------------------------------------------------
// funkcja pobiera z pliku wypowiedz o danym $id
function get_post( $id, 
                   $datafile="wypowiedzi.txt", $separator=":-:" )
{
    $data = file( $datafile );
    $post=FALSE;
    foreach($data as $v ){
       $r = explode( $separator, trim($v));
       if( $r[0]==$id ){
           $post = array( 
                 "postid"  => $r[0],
                 "topicid" => $r[1],
                 "post"    => hex2bin($r[2]),
                 "username"=> hex2bin($r[3]),
                 "date"    => $r[4]
              );
            break;  
       }
    }
    return $post; 
}

//------------------------------------------------------------------------------
// funkcja aktualizuje w pliku dane dla wypowiedzi o danym $postid
function update_post( $postid, $post, $username, 
                      $datafile="wypowiedzi.txt", $separator=":-:")
{
    $data=file( $datafile ); 
    $new_post=FALSE;
    foreach($data as $k=>$v ){
       $r = explode( $separator, trim($v));
       if( $r[0]==$postid ){
           $new_post = array( 
                 "postid"  => $r[0],
                 "topicid" => $r[1],
                 "post"    => bin2hex($post),
                 "username"=> bin2hex($username),
                 "date"    => date("Y-m-d H:i:s")
              );
              $data[$k] = implode($separator,$new_post)."\n";
              file_put_contents($datafile, implode("", $data));  
            break;  
       }
    }
    return $new_post; 
}

//------------------------------------------------------------------------------
// funkcja usuwa z pliku dane dla wypowiedzi o danym $id
function delete_post( $id, 
                      $datafile="wypowiedzi.txt", $separator=":-:")
{
   if( $data=file( $datafile ) ){
      foreach($data as $k=>$v){
         $r = explode( $separator, trim($v));
         if( $r[0]==$id ){
            unset($data[$k]);
            break;
         }   
      }
      return file_put_contents($datafile,implode("", $data)); 
   }else{
      return FALSE;
   }   
}

//------------------------------------------------------------------------------
// funkcja zlicza wypowiedzi na ka�dy z temat�w
function get_posts_count( $datafile="wypowiedzi.txt", $separator=":-:" )
{
   if( !is_file($datafile) ) 
      return FALSE;
   $post_count = array();   
   if( $data=file( $datafile ) ){
      foreach( $data as $v ){
         if( strlen(trim($v))>0 ){
           $p = explode( $separator, trim($v));
           if( isset($post_count[$p[1]]) )
             $post_count[$p[1]] = $post_count[$p[1]] + 1;
           else
             $post_count[$p[1]] = 1;
         }
      }
      return $post_count; 
   }else{
      return FALSE;
   }
}

//------------------------------------------------------------------------------
// funkcja pobiera date ostatniej wypowiedzi
function get_last_post_date($datafile="wypowiedzi.txt", $separator=":-:")
{
    if( $data=file( $datafile ) ){
        $record = explode( $separator, trim(array_pop($data)));
        return $record[4];
    }else{
        return '- brak postów -';
    } 
}
