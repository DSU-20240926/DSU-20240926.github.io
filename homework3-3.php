<!DOCTYPE html>
<head>
    <meta charset="UTF-8">
    <title>homework3-3.php</title>
</head>
<html>
<body>
    <div style="text-align: center; margin-top: 100px;">
        <?php
            /* 100 이하의 정수 숫자 n 을 입력받아 n개의 피보나치 수열과 앞과 뒤항의 비례를 출력하세요

            fi+2 = fi+1 + fi
            i  fi  fi+1 / fi
            1 1 1
            2 1 2
            3 2 1.5
            4 3 1.666667
            5 5 1.6
            6 8 */

            $n = $_GET["num"];
            echo "num = {$n} <br><br><pre>";  // <pre> 시작

            $a = 1; $b = 1;
            printf("%-3s %-5s %-10s\n", "i", "f(i)", "f(i+1)/f(i)"); //헤더 줄
            for ($i = 1; $i <= $n; $i++) {
                if ($a != 0 && $i < $n) {
                    $ratio = round($b / $a, 6);
                    printf("%-3d %-5d %-10s\n", $i, $a, $ratio);
                } else {
                    printf("%-3d %-5d %-10s\n", $i, $a, "");
                }
                $c = $a + $b;
                $a = $b;
                $b = $c;
            }
            echo "</pre>"; //<pre>끝
        ?>
    </div>
</body>