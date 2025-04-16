<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>homework3-2.php</title>
</head>
<html>
<body>
    <div style="text-align: center; margin-top: 100px;">
        <?php
            $n = $_GET["num"];

            echo("num = {$n} <br><br>");
            for($i = 0; $i < $n; $i++) {
                $arr[$i] = rand(0, 100);
                echo("arr[{$i}] = {$arr[$i]} <br>");
            }
            sort($arr); echo "<br>sort<br>";
            for($i = 0; $i < $n; $i++) {
                echo("arr[{$i}] = {$arr[$i]} <br>");
            }
        ?>
    </div>
</body>