<?php

define("HOST", "http://127.0.0.1/");
define("USER", "root");
define("PASSWORD", "autoset");
define("DB_NAME", "yjc_test");

$reload = $_GET['mode'];

$db_con = mysqli_connect(HOST, USER, PASSWORD);


switch ($reload) {
    case "reload":
        reload(null);
        break;

    case "input":
        input();
        reload(null);
        break;

    case "delete" :
        $number = $_GET['number'];
        deleterow($number);
        reload(null);
        break;

    case "asc" :
        reload("select * from student order by sum");
        break;

    case "desc" :
        reload("select * from student order by sum desc");
        break;
}


function input()
{
    $name = $_GET['name']; // 학생이름
    $korean = $_GET['korean']; // 국어 성적
    $english = $_GET['english']; // 영어 성적
    $math = $_GET['math']; // 수학 성적
    $sum = $korean + $english + $math;
    $avg = $sum / 3;

    //$db_con = mysqli_connect(HOST, USER, PASSWORD);

    global $select;

    mysqli_select_db($select, DB_NAME);

    $sql = "insert into student(name,korean,english,math,sum,avg) values";
    $sql .= "('$name','$korean','$english','$math','$sum','$avg')";


    $result = mysqli_query($select, $sql);

    mysqli_close($select);

}


function reload($send)
{
    echo "<table width=\"1000\" border=\"1\" style=\"border-collapse: collapse; 
text-align: center\" id=\"gradeTable\">
<tr>
    <td>번호</td>
    <td>이름</td>
    <td>국어</td>
    <td>영어</td>
    <td>수학</td>
    <td>합계</td>
    <td>평균</td>
    <td>삭제</td>
</tr>
</table>";

    global $select;

    mysqli_select_db($select, DB_NAME); // use yjc_test와 동일, 데이터베이스 선택

    $sql = $send == null ? "select * from student" : $send;

    $result = mysqli_query($select, $sql);

    $sql_num_rows = mysqli_num_rows($result);

    for ($i = 0; $i < $sql_num_rows; $i++) {

        $result_array   = mysqli_fetch_array($result);
        $number         = $result_array['num'];
        $name           = $result_array['name'];
        $korean         = $result_array['korean'];
        $english        = $result_array['english'];
        $math           = $result_array['math'];
        $sum            = $result_array['sum'];
        $average        = round($result_array['avg'], 3);

        echo "<tr><td>$number</td><td>$name</td><td>$korean</td><td>$english</td>
    <td>$math</td><td>$sum</td><td>$average</td>
    <td><input type='button' value='삭제' onclick='deleterow($number)'></td></tr>";
    }
}


function deleterow($number)
{
    global $select;

    mysqli_select_db($select, DB_NAME); // use yjc_test와 동일, 데이터베이스 선택

    $sql = "delete from student where num=$number";

    $result = mysqli_query($select, $sql);

    mysqli_close($select);
}


?>