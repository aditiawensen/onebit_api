<?
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");

include('aw-config/config.php');

$level = $_GET['level'];
$parent = $_GET['parent'];
$result = $con->query("SELECT pages.id id_pages, pages.title title_pages, pages.link link, parent.id id_parent, pages.img img_pages FROM access,access_level,pages,parent WHERE access.access_level=access_level.id AND access.pages=pages.id AND pages.parent=parent.id AND access_level.title='$level' AND parent.id='$parent'");

$outp = "";
$row = 0;
while($rs = $result->fetch_array(MYSQLI_ASSOC)) {
    $row+=1;
    if ($outp != "") {$outp .= ",";}
    $outp .= '{"id_pages":"' . $rs["id_pages"] . '",';
    $outp .= '"title":"' . $rs["title_pages"] . '",';
    $outp .= '"link":"' . $rs["link"] . '",';
    $outp .= '"id_parent":"' . $rs["id_parent"] . '",';
    $outp .= '"image":"' . $rs["img_pages"] . '"}';
}
$outp ='['.$outp.']';
$con->close();

echo($outp);
?>