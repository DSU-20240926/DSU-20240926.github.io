<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>homework3-4.php</title>
</head>
<html>
<body>
    <div style="text-align: center; margin-top: 100px;">
        <?php
            $PI = 3.1415926535897932384626433832795028841971;
            $tri_wid = $_GET["tri_wid"];
            $tri_hei = $_GET["tri_hei"];
            $tri_res = $tri_wid * $tri_hei / 2;

            $squ_wid = $_GET["squ_wid"];
            $squ_hei = $_GET["squ_hei"];
            $squ_res = $squ_wid * $squ_hei;

            $cir_rad = $_GET["cir_rad"];
            $cir_res = $PI * ($cir_rad ** 2);

            $rec_wid = $_GET["rec_wid"];
            $rec_hei = $_GET["rec_hei"];
            $rec_len = $_GET["rec_len"];
            $rec_res = $rec_wid * $rec_hei * $rec_len;

            $cyl_rad = $_GET["cyl_rad"];
            $cyl_hei = $_GET["cyl_hei"];
            $cyl_res = $PI * ($cyl_rad ** 2) * $cyl_hei;

            $sph_rad = $_GET["sph_rad"];
            $sph_res = (4 / 3) * ($PI * ($sph_rad ** 3));

            echo("삼각형 면적 = width*height/2 = {$tri_wid}*{$tri_hei}/2 = {$tri_res} <br><br>");
            echo("직사각형 면적 = width*height = {$squ_wid}*{$squ_hei} = {$squ_res} <br><br>");
            echo("원 면적 = pi*radius^2 = {$PI}*{$cir_rad}^2 = {$cir_res} <br><br>");
            echo("직육면체 체적 = width*height*length = {$rec_wid}*{$rec_hei}*{$rec_len} = {$rec_res} <br><br>");
            echo("원통 체적 = pi*radius^2*height = {$PI}*{$cyl_rad}^2*{$cyl_hei} = {$cyl_res} <br><br>");
            echo("구 체적 = 4/3*pi*radius^3 = 4/3*{$PI}*{$sph_rad}^3 = {$sph_res} <br><br>");
        ?>
    </div>
</body>