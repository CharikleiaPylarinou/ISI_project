<?php

session_start();

if(!isset($_POST['submit'])){
	echo '<script>window.location.href="welcome.php"; window.alert("Don\'t do that!");</script>';
}	 

if (!isset($_SESSION["username"]) && !isset($_SESSION["password"])){
	exit("You have to login first!");
}

set_time_limit(120);
$mysql_link=new mysqli('localhost','root','','map');
 
if(mysqli_connect_error())
{
    die('Connect Error ('.mysqli_connect_errno().')'.mysqli_connect_error());
}
 
mysqli_set_charset($mysql_link, "utf8");

$fileToUpload=$_POST["fileToUpload"];
$myarray = explode(".",$fileToUpload);

//accepta solamente KML archivo
if($myarray[1] != "kml"){
	echo '<script>window.location.href="options.php"; window.alert("Incorrect type of file. Please insert a \"\.kml\" file.");</script>';
}

$kml=simplexml_load_file($fileToUpload) or die("Error: Cannot create object");
 
$placemarks=$kml->Document->Folder->Placemark;
 
$pl_name = 1;
//cogemos la populacion
foreach($placemarks as $placemark){
    $descr= $placemark->description;
    $description=(string)$descr;
    if(strpos($description,'Population')!==false){
        $dom=new DOMDocument;
        $dom->loadHTML($description);
        $li = $dom->getElementsByTagName('li');
        $population=$li[sizeof($li)-1]->getElementsByTagName('span');
        $popul=$population[1]->nodeValue;
    }
    else{
        $popul=0;
         
    }
 
    $multigeometries=$placemark->MultiGeometry;

    //para cada MultiGeometry calculan los coordinaciones de cada poligono usando funciones 
    //calculo de centroide
    foreach($multigeometries as $multigeometry){
        //mas de uno MultiGeometry
        if(isset($multigeometries->MultiGeometry)){
            $coordinates = $multigeometry->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
        }
        else{
            $coordinates = $multigeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;   
        }
        $value    = (string)$coordinates; 
        $args  = explode(" ", trim($value));
        $coord_x=array();
        $coord_y=array();
        for($ar=0;$ar<sizeof($args);$ar++){
            $args1  = explode(",", trim($args[$ar]));
            //coordinacion x de poligono
            $coord_y[$ar]=$args1[0];
            //coordinacion y de poligono
            $coord_x[$ar]=$args1[1];
        }
        //llamada de funciones para el calculo de centroide 
        $centroid=getCentroidOfPolygon($coord_y,$coord_x);
        //cordinacion x de centroide
        $centroid_y=$centroid[0];
        //scordinacion y de centroide
        $centroid_x=$centroid[1];
    }
     
    $total_spots=rand(20,120);		//los totales aparcamientos de cada poligono se calculan aleatoriamente 
	$avail_spots=$total_spots-$popul*0.2;		//los aparcamientos disponible de cada poligono
	
	//elegxetai an oi kateilhmmenes 8eseis ksepernoun tis dia8esimes logw puknothtas plh8usmou
        //
	if($avail_spots<0){
		$avail_spots=0;	
	}
	
        //probabilidad grande el poligono está en el centro
        //probabilidad entre grande y minima el poligono está en lugar donde gente vive
        //probabilidad minima el poligono está en lugar que tiene estable demanda
	if (rand(0, 7) <= 3) { 
		$kind_id = 1;
	} elseif((rand(0, 7) >= 4)&&(rand(0, 7) <= 6)){
		$kind_id = 2;
	}
	else{
		$kind_id = 3;
	}
	
	$my_query1="INSERT into polygon VALUES(null, $popul, $centroid_x, $centroid_y,$total_spots,$avail_spots,$kind_id)";
    $result=$mysql_link->query($my_query1);
 
    if(!$result)
        die('Invalid query:'.$mysql_link->error);
    
     
    $multigeometries=$placemark->MultiGeometry;
     
    foreach($multigeometries as $multigeometry){
        if(isset($multigeometries->MultiGeometry)){
            $coordinates = $multigeometry->MultiGeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;
        }
        else{
            $coordinates = $multigeometry->Polygon->outerBoundaryIs->LinearRing->coordinates;   
        }
        $value =(string)$coordinates; 
        $args = explode(" ", trim($value));

        for($ar=0;$ar<sizeof($args);$ar++){
            $args1  = explode(",", trim($args[$ar]));
            $my_query="INSERT INTO coordinates VALUES(null, $pl_name, $args1[1], $args1[0])";
            $result=$mysql_link->query($my_query);
 
            if(!$result)
                die('Invalid query:'.$mysql_link->error);
        }
         
    }
     
    $pl_name++;
}
 
//calculo de cada poligono
function getAreaOfPolygon($coord_x,$coord_y) {
    $area = 0;
    $vl=sizeof($coord_x);
     
    //cada poligono tiene muchas cordinaciones
    for ($vi=0; $vi<$vl; $vi++) {
        $thisx = $coord_x[$vi];
        $thisy = $coord_y[$vi];
        $nextx = $coord_x[($vi+1) % $vl];
        $nexty = $coord_y[($vi+1) % $vl];
        $area += ($thisx * $nexty) - ($thisy * $nextx);
    }
    
    $area = abs(($area / 2));
    return $area;
}
 
//calculo de centrode de cada poligono
function getCentroidOfPolygon($coord_x,$coord_y) {
    $cx = 0;
    $cy = 0;
    $vl=sizeof($coord_x);
     
    for ($vi=0;$vi<$vl; $vi++) {
        $thisx = $coord_x[$vi];
        $thisy = $coord_y[$vi];
        $nextx = $coord_x[($vi+1) % $vl];
        $nexty = $coord_y[($vi+1) % $vl];
 
        $p = ($thisx * $nexty) - ($thisy * $nextx);
        $cx += ($thisx + $nextx) * $p;
        $cy += ($thisy + $nexty) * $p;
    }
 
    $area = getAreaOfPolygon($coord_x,$coord_y);
    $cx = -$cx / ( 6 * $area);
    $cy = -$cy / ( 6 * $area);
 
    return array($cx,$cy);  
 }

$mysql_link->close();
$_SESSION["newAccess"] = ['yes'];
echo '<script>window.location.href="options.php"; window.alert("Successful upload");</script>';
?>