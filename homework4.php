<!-- 테이블 생성 (최초 1회만 실행되게 주석 처리함)
$mysqli->query("
    CREATE TABLE IF NOT EXISTS `tickets` (
        `id` int(11) NOT NULL AUTO_INCREMENT,
        `name` varchar(50) DEFAULT NULL,
        `category` varchar(50) DEFAULT NULL,
        `child_price` int(11) DEFAULT NULL,
        `adult_price` int(11) DEFAULT NULL,
        `child_qty` int(11) DEFAULT NULL,
        `adult_qty` int(11) DEFAULT NULL,
        `note` varchar(100) DEFAULT NULL,
        `latest_update` bigint(20) NOT NULL DEFAULT unix_timestamp(),
        PRIMARY KEY (`id`)  
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_general_ci;
"); -->

<?php
    // DB 연결
    $mysqli = new mysqli("localhost:3307", "root", "", "tsphp");
    if ($mysqli->connect_errno) {
        die("DB 연결 실패: " . $mysqli->connect_error);
    }

    $mysqli->set_charset("utf8mb4"); //문자셋을 UTF-8로 설정

    // POST 요청 처리
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $name = $_POST['name']; //이름 입력값 받기
        $tickets = $_POST['ticket'];

        foreach ($tickets as $ticket) {
            if ((int)$ticket['child_qty'] === 0 && (int)$ticket['adult_qty'] === 0) {continue;} //수량이 모두 0이면 DB에 저장하지 않음

            $stmt = $mysqli->prepare("INSERT INTO tickets (name, category, child_price, adult_price, child_qty, adult_qty, note) VALUES (?, ?, ?, ?, ?, ?, ?)");
            $stmt->bind_param("sssiiis", $name, $ticket['category'], $ticket['child_price'], $ticket['adult_price'], $ticket['child_qty'], $ticket['adult_qty'], $ticket['note']);
            $stmt->execute();
        }
    }

    // 티켓 종류 정의
    $data = [
        ["입장권", 7000, 10000, "입장"],
        ["BIG3", 12000, 18000, "입장+놀이3종"],
        ["자유이용권", 21000, 26000, "입장+놀이자유"],
        ["연간이용권", 70000, 90000, "입장+놀이자유"]
    ];

    // 콤보박스 옵션 (0~10)
    function selectBox($name, $type, $index) {
        $id = $type . '-select-' . $index;
        $html = "<select name='$name' id='$id' data-index='$index' data-type='$type'>";
        for ($i = 0; $i <= 10; $i++) {
            $html .= "<option value='$i'>$i</option>";
        }
        $html .= "</select>";
        return $html;
    }
?>

<!-- 입력 폼 (표 형태) -->
<style>
    body {
        background-color: pink;
    }

    .large-button {
        width: 80px;
        height: 50px;
        margin: 0 40px;
        font-size: 20px;
        padding: 10px 10px;
        border-radius: 10px;
        border-color: #45a049;
        border-style: solid;
    }
    .large-button:hover {
        background-color: #45a049;
        transform: scale(1.2);
        cursor: pointer;
    }
</style>
<div style="text-align: center;">
    <form method="post" style="text-align: center; margin-top: 100px;">
        <label for="name">이름: </label>
        <input type="text" name="name" id="name" required>
        <br><br>

        <table border="1" cellpadding="5" cellspacing="0" style="display: inline-table; text-align: center;">
            <tr>
                <th>No</th>
                <th>구분</th>
                <th>어린이</th>
                <th>어른</th>
                <th>비고</th>
                <th>합계</th>
            </tr>
            <?php foreach ($data as $index => $item): ?>
                <tr>
                    <td><?= $index + 1 ?></td>
                    <td><?= $item[0] ?>
                        <input type="hidden" name="ticket[<?= $index ?>][category]" value="<?= $item[0] ?>">
                        <input type="hidden" name="ticket[<?= $index ?>][child_price]" value="<?= $item[1] ?>">
                        <input type="hidden" name="ticket[<?= $index ?>][adult_price]" value="<?= $item[2] ?>">
                        <input type="hidden" name="ticket[<?= $index ?>][note]" value="<?= $item[3] ?>">
                    </td>
                    <td>
                        <span class="child-price" id="child-price-<?= $index ?>">0원</span>
                        <?= selectBox("ticket[$index][child_qty]", "child", $index) ?>
                    </td>
                    <td>
                        <span class="adult-price" id="adult-price-<?= $index ?>">0원</span>
                        <?= selectBox("ticket[$index][adult_qty]", "adult", $index) ?>
                    </td>

                    <td><?= $item[3] ?></td>
                    <td><span class="total-price">0원</span></td>
                </tr>
            <?php endforeach; ?>
        </table>
        <br><br>
        <input class="large-button" type="submit" value="제출">
    </form>

    <script>
        document.querySelectorAll('select').forEach(select => {
            select.addEventListener('change', function () {
                const index = this.dataset.index;
                const type = this.dataset.type;
                const qty = parseInt(this.value);

                const price = parseInt(document.querySelector(`input[name="ticket[${index}][${type}_price]"]`).value);
                const amount = qty * price;

                document.getElementById(`${type}-price-${index}`).textContent = amount.toLocaleString() + '원';

                // 금액 계산 후 각 셀 업데이트
                const childQty = parseInt(document.getElementById(`child-select-${index}`).value);
                const adultQty = parseInt(document.getElementById(`adult-select-${index}`).value);

                const childPrice = parseInt(document.querySelector(`input[name="ticket[${index}][child_price]"]`).value);
                const adultPrice = parseInt(document.querySelector(`input[name="ticket[${index}][adult_price]"]`).value);

                const total = (childQty * childPrice) + (adultQty * adultPrice);

                // 해당 행의 합계 칸 업데이트
                const totalSpan = document.querySelectorAll('.total-price')[index];
                if (totalSpan) {
                    totalSpan.textContent = total.toLocaleString() + '원';
                }
            });
        });
    </script>
</div>

<!-- 저장된 내역 출력 -->
<?php
    echo "<hr>\n<h3 style='text-align: center;'>--- 구매 내역 ---</h3>\n";

    $result = $mysqli->query("SELECT * FROM tickets ORDER BY latest_update");

    echo "<div style='text-align: center;'>";
    echo "<table border='1' cellpadding='5' cellspacing='0' style='margin: 0 auto; text-align: center;'>";
    echo "<tr>
            <th>No</th>
            <th>이름</th>
            <th>구분</th>
            <th>어린이</th>
            <th>어른</th>
            <th>비고</th>
            <th>합계</th>
            <th>구매 일자</th>
          </tr>";

    $no = 1;
    while ($row = $result->fetch_assoc()) {
        $child_total = $row['child_price'] * $row['child_qty'];
        $adult_total = $row['adult_price'] * $row['adult_qty'];
        $total = $child_total + $adult_total;

        if (stristr(PHP_OS, 'WIN')) {
            // Windows 시스템
            //$timezone = trim(exec('tzutil /g'));
            $timezone = "Asia/Seoul";
        } else {
            // Linux 시스템
            $timezone = trim(exec('cat /etc/timezone'));
        }
        date_default_timezone_set($timezone); //또는 서버의 지역에 맞는 시간대 설정
        $latest_update = date('Y-m-d H:i:s', $row['latest_update']);

        echo "<tr>
                <td>{$no}</td>
                <td>{$row['name']}</td>
                <td>{$row['category']}</td>
                <td>{$row['child_qty']}명</td>
                <td>{$row['adult_qty']}명</td>
                <td>{$row['note']}</td>
                <td>{$total}원</td>
                <td>{$latest_update}</td>
              </tr>";
        $no++;
    }
    echo "</table></div>";
?>