<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body>
    ここはグラフ描画用のページです．
    <canvas id="lineChart"></canvas>

    <?php
        ob_start();
            include 'student.php';
        ob_clean();
        //print_r($databasearray1);
    ?>
    <script>
        let lineCtx = document.getElementById("lineChart");
        var data_array = <?php echo json_encode($databasearray1);?>;
        console.log(data_array);
        console.log(data_array[0]["UID"]);

        // 線グラフの設定
        let lineConfig = {
          type: 'line',
          data: {
            // ※labelとデータの関係は得にありません
            labels: ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'],
            datasets: [{
              label: 'Red',
              data: [20, 35, 40, 30, 45, 35, 40],
              borderColor: '#f88',
            }, {
              label: 'Green',
              data: [20, 15, 30, 25, 30, 40, 35],
              borderColor: '#484',
            }, {
              label: 'Blue',
              data: [30, 25, 10, 5, 25, 30, 20],
              borderColor: '#48f',
            }],
          },
          options: {
            scales: {
              // Y軸の最大値・最小値、目盛りの範囲などを設定する
              y: {
                suggestedMin: 0,
                suggestedMax: 60,
                ticks: {
                  stepSize: 20,
                }
              }
            },
          },
        };
        let lineChart = new Chart(lineCtx, lineConfig);
    </script>
</body>
</html>