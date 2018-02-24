<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('aw-config/config.php');

$level = $_GET['level'];
$result = $con->query("SELECT parent.id id_parent, parent.title title FROM access,access_level,pages,parent WHERE access.access_level=access_level.id AND access.pages=pages.id AND pages.parent=parent.id AND access_level.title='$level' GROUP BY title ORDER BY parent.sort ASC");

$outp = "";
$row = 0;
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    $row+=1;
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id_parent":"' . $rs["id_parent"] . '",';
    $outp .= '"title":"' . $rs["title"] . '"}';
}
$outp ='['.$outp.']';
$con->close();

echo($outp);
?>