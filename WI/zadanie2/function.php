<?php
function get_posts($topicid, $datafile="wypowiedzi.txt", $separator=":-:")
    {
       if( $data=file( $datafile ) ){
          $posts=array();
          foreach($data as $line){
              $record = explode( $separator, trim($line));
              if( $record[1]==$topicid ){
                  $posts[]=array( 
                     "postid"  => $record[0],
                     "topicid" => $record[1],
                     "post"    => hex2bin($record[2]),
                     "username"=> hex2bin($record[3]),
                     "date"    => $record[4]
                  );
              }
          }
          return $posts;   
       }else{
          return FALSE;
       }
    }
    function get_topic($datafile="topic.txt", $separator=":-:")
    {
        if ($data = file($datafile))
        {
            $topics = array();
            foreach ($data as $line)
            {
                $record = explode($separator, trim($line));
                {
                    $topic = array(
                        "topicid" => $record[0],
                        "topic" => hex2bin($record[1]),
                        "topic_body" => hex2bin($record[2]),
                        "username" => hex2bin($record[3]),
                        "date" => $record[4]
                    );
                    $topics[] = $topic;
                }
            }
            return $topics;
        } else {
            return FALSE;
        }
    }
    function couteroftopic()
    {
        $count = 0;
        $file = fopen("topic.txt", "r");
        while(!feof($file))
        {
            $line = fgets($file);
            if(trim($line) != "")
            {
                $count++;
            }
        }
        fclose($file);
        return $count;
    }
    function couterofpost($topicid, $datafile="wypowiedzi.txt", $separator=":-:")
    {
        $count = 0;
        if ($data = file($datafile))
        {
            foreach ($data as $line)
            {
                $record = explode($separator, trim($line));
                if ($record[1] == $topicid)
                {
                    $count++;
                }
            }
        }
        return $count;
    }
function put_post($topicid, $post, $username, $datafile="wypowiedzi.txt", $separator=":-:")
    {
       if( is_file($datafile) ){
          $data=file( $datafile );
          $record = explode( $separator, trim(array_pop($data))); 
          $postid = $record[0]+1;
       }else{
          $postid = 1;    
       }
       $data = implode( $separator, 
                         array( $postid, 
                                $topicid, 
                                bin2hex($post), 
                                bin2hex($username), 
                                date("Y-m-d H:i:s") 
                        )
                      );
       if( $fh = fopen( $datafile, "a+" )){
          fwrite($fh, $data."\n");
          fclose($fh);
          return $postid;
       }else{
          return FALSE;
       };                               
    }
    function put_topic($topic, $topic_body, $username, $datafile="topic.txt", $separator=":-:")
    {
       if( is_file($datafile) ){
          $data=file( $datafile );
          $record = explode( $separator, trim(array_pop($data))); 
          $topicid = $record[0]+1;
       }else{
          $topicid = 1;    
       }
       $data = implode( $separator, 
                         array( $topicid,
                                bin2hex($topic),
                                bin2hex($topic_body), 
                                bin2hex($username), 
                                date("Y-m-d H:i:s") 
                        )
                      );
       if( $fh = fopen( $datafile, "a+" )){
          fwrite($fh, $data."\n");
          fclose($fh);
          return $topicid;
       }else{
          return FALSE;
       };                               
    }
function editLineInFile($id, $post, $username)
{
    $datafile = "wypowiedzi.txt";
    $separator = ":-:";
    $posts = array();

    if ($data = file($datafile)) {
        foreach ($data as $line) {
            $record = explode($separator, trim($line));
            if ($record[0] == $id) {
                $record[2] = bin2hex($post);
                $record[3] = bin2hex($username);
                $line = implode($separator, $record);
            }
            $posts[] = $line;
        }
        $fh = fopen($datafile, "w");
        fwrite($fh, implode("\n", $posts));
        fclose($fh);
    }
}
?>