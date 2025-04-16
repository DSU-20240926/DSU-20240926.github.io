<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>homework3-1.php</title>
</head>
<html>
<body>
    <div style="text-align: center; margin-top: 100px;">
        <?php
            $n = $_GET["num"]; $hap = 0; $gop = 1;

            for($i = 1; $i < $n; $i++) {
                $hap += $i;
                echo("{$i} + ");
            }
            $hap += $n;
            echo("{$n} = ${hap}<br>");

            for($i = 1; $i < $n; $i++) {
                $gop *= $i;
                echo("{$i} * ");
            }
            $gop *= $n;
            echo("{$n} = {$gop}<br>");
        ?>
    </div>
</body>