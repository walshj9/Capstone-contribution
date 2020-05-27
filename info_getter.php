<?php
$title = $_GET['title']; 
$year = $_GET['year']; 
$url = 'http://www.omdbapi.com/?apikey=674c930e&t=';
$test = '';
$gross='';

if (!is_null($year)){
	$title =strtolower($title);
	$title = ucwords($title);
	$title= preg_replace('/\s+/', '+', $title);
	$test = $url.$title;}
else{
	$title = preg_replace('/\s+/', '+', $title);
	$test = $url.$title."&y=".$year;}

$response = file_get_contents($test);
$obj = json_decode($response);
if(strcmp($obj->Response, "False")===0){
	exit('Invalid Movie Title');
} 
$movie_title = $obj->Title;
$release_year = $obj->Year;
$movie_rating =$obj->Ratings[0]->Value;
$poster_link = $obj->Poster;
$gross = $obj->BoxOffice;

$address = "localhost";
$username = "root";
$password = "";
$server  = mysqqli_connect($address, $username, $password);
//, "movie_ratings", "3306"
//print_r($server);//mysqlnd v7.4.2 
$dupecheck = "SELECT * FROM movie_ratings WHERE (Title = '$movie_title' AND Release_Year = '$release_year')";
$duperaw = mysql_query( $dupecheck); 
// $server,
if(mysql_num_rows($duperaw) >0 ) {
      echo("$movie_title already exists in the table \n");} 
else{
		$sql = "INSERT INTO Movie_Ratings (Title,Release_Year,IMDB_rating,BoxOffice,Poster_Link) VALUES('$movie_title','$release_year','$movie_rating','$gross', '$poster_link')";
		if(mysql_query( $sql)){echo "Added {$movie_title} to DB";} 
//$server,
		else{echo "ERROR: Unable to execute sql. ";}}
//	 . mysqli_error($server)
mysql_close($server);
?>