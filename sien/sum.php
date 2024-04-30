<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<html>
<head>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@3.9.1/dist/chart.min.js"></script>
</head>
<body>
    ここは結果確認用のページです．

    <?php
        ob_start();
            include 'student.php';
        ob_clean();
        //print_r($databasearray1);


        //合計を求めるプログラム
        $totals = array();
        foreach($databasearray1 as $data){
            $user = $data['UID'];
            if(!isset($totals[$user])){
                $totals[$user] = 0;
            }
            $totals[$user] += $data['Time'];
        }

        foreach($totals as $user => $total){
            echo $user . ':' . $total . "<br>";
        }


    ?>


    <script>
        var data_array = <?php echo json_encode($databasearray1);?>;
        console.log(data_array);
        console.log(data_array[0]["UID"]);


        var totals = {};
        var n = 0;

        data_array.forEach(function(data){
            var user = data_array[n]["UID"];
            var timeoriginal = data_array[n]["Time"];
            console.log(user + ':' + time);
            var time = parseInt(timeoriginal,10);
            
            if(!totals[user]){
                totals[user] = 0;
            }
            totals[user] += time;
            //console.log(user + ':' + totals[user]);
            
            n += 1;

        })

        for(var user in totals){
            console.log(user + ':' + totals[user]);
        }
    </script>

</body>
</html>