<?php
$servername = "localhost";
$username = "root";
$password = ""; 

$title = $_GET['title']; 
$year = $_GET['year']; 
$url = 'http://www.omdbapi.com/?apikey=674c930e&t=';
$resp_link = '';
$gross='';

if (!is_null($year)){
	$title =strtolower($title);
	$title = ucwords($title);
	$title= preg_replace('/\s+/', '+', $title);
	$resp_link = $url.$title;}
else{
	$title = preg_replace('/\s+/', '+', $title);
	$resp_link = $url.$title."&y=".$year;}

$response = file_get_contents($resp_link);
$obj = json_decode($response);
if(strcmp($obj->Response, "False")===0){
	exit('Invalid Movie Title');
} 
$movie_title = $obj->Title;
$release_year = $obj->Year;
$movie_rating =$obj->Ratings[0]->Value;
$poster_link = $obj->Poster;
$gross = $obj->BoxOffice;
//PRODUCTION
$server  = mysqli_connect($servername, $username, $password, "movie_ratings", "3306");
//, "movie_ratings", "3306"
//print_r($server);->mysqlnd v7.4.2 
$dupecheck = "SELECT * FROM `movie_ratings` WHERE (Title = '$movie_title' AND Release_Year = '$release_year')";
$duperaw = mysqli_query($server, $dupecheck); 
 
if(!$duperaw || mysqli_num_rows($duperaw) > 0 ) { 
       echo("$movie_title already exists in the table \n");
}
else{
		$sql = "INSERT INTO `movie_ratings` (Title,Release_Year,IMDB_rating,BoxOffice,Poster_Link) VALUES('$movie_title','$release_year','$movie_rating','$gross', '$poster_link')";
		if(mysqli_query($server, $sql)){echo "Added {$movie_title} to DB";}
		else{echo "ERROR: Unable to execute sql. ". mysqli_error($server);}}
		
mysqli_close($server);
?>
