<?php  

if((isset($_GET['start']))&&(isset($_GET['second'])&&$_GET['end']!="")){ 
    function get_web_page( $url )
    {
        $user_agent='Mozilla/5.0 (Windows NT 6.1; rv:8.0) Gecko/20100101 Firefox/8.0';

        $options = array(

            CURLOPT_CUSTOMREQUEST  =>"GET",        //set request type post or get
            CURLOPT_POST           =>false,        //set to GET
            CURLOPT_USERAGENT      => $user_agent, //set user agent
            CURLOPT_COOKIEFILE     =>"cookie.txt", //set cookie file
            CURLOPT_COOKIEJAR      =>"cookie.txt", //set cookie jar
            CURLOPT_RETURNTRANSFER => true,     // return web page
            CURLOPT_HEADER         => false,    // don't return headers
            CURLOPT_FOLLOWLOCATION => true,     // follow redirects
            CURLOPT_ENCODING       => "",       // handle all encodings
            CURLOPT_AUTOREFERER    => true,     // set referer on redirect
            CURLOPT_CONNECTTIMEOUT => 120,      // timeout on connect
            CURLOPT_TIMEOUT        => 120,      // timeout on response
            CURLOPT_MAXREDIRS      => 10,       // stop after 10 redirects
        );

        $ch      = curl_init( $url );
        curl_setopt_array( $ch, $options );
        $content = curl_exec( $ch );
        $err     = curl_errno( $ch );
        $errmsg  = curl_error( $ch );
        $header  = curl_getinfo( $ch );
        curl_close( $ch );

        $header['errno']   = $err;
        $header['errmsg']  = $errmsg;
        $header['content'] = $content;
        return $header;
    }


 $mysqli_link = mysqli_connect("127.0.0.1", "root", "local", "mobile");
    if (mysqli_connect_errno()) {
      echo "Failed to connect to MySQL: " . mysqli_connect_error();
    exit();
    }

if (!$mysqli_link->set_charset("utf8")) {
    printf("Ошибка при загрузке набора символов utf8: %s\n", $mysqli_link->error);
    exit();
} 



$start=$_GET['start'];
$str1= $_GET['second'];
$str2= $_GET['second2'];
$end=$_GET['end'];

foreach (range($str1, $str2) as $number)
{ $realnum="+7".$start.$number.$end;

$url= "http://defcodes.ru/".$start.$number.$end;
$result = get_web_page( $url );
if ( $result['errno'] != 0 ) {}
//    ... error: bad url, timeout, redirect loop ...

if ( $result['http_code'] != 200 ) {}
//    ... error: no page, no permissions, no service ...
$page = $result['content'];
echo "\n";
preg_match_all('/(?<=text-danger">).+?(?=\<\/span>)/', $page, $matches);

echo $realnum." ";
$str=(string)$matches[0][0];
print_r($str);
echo "<br/>";

$query2="INSERT INTO mobile (Num, Whose) SELECT '".$realnum."', '".$str."' FROM DUAL WHERE NOT EXISTS (SELECT * FROM `mobile` WHERE Num='".$realnum."' AND Whose='".$str."') LIMIT 1";
mysqli_query($mysqli_link, $query2);
usleep(500000);
}


}
?>