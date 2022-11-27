<!DOCTYPE html>
<html>
<head>
    <title>Astroid NEO</title>
</head>
<body>
    <form method="post" action="/api">
        @csrf
        <input type="date" name="s_date">
        <input type="date" name="e_date">
        <input type="submit" name="submit_form">
    </form>
    <p><?php 
        if($apidata != ''){
            $fastestSpeed = 0;
            $fastestAstroidId = '';
            $closest_from_earth = 9999999999;
            $closestAstroidId = '';
            $averageSize = 0;
            $avgSize = 0;
            $date_range = '';
            $count_range = '';
            $data_from_api_array = json_decode($apidata,true);
            ksort($data_from_api_array['near_earth_objects']);

            foreach($data_from_api_array['near_earth_objects'] as $keyone=>$astroid_list){
                $count = 0;

                foreach($astroid_list as $key=>$astroid_data){
                    ++$count;
                    $speed = $astroid_data['close_approach_data'][0]['relative_velocity']['kilometers_per_hour'];
                    $distance_from_earth = $astroid_data['close_approach_data'][0]['miss_distance']['kilometers'];
                    $avgSize = ($astroid_data['estimated_diameter']['kilometers']['estimated_diameter_min']+$astroid_data['estimated_diameter']['kilometers']['estimated_diameter_max'])/2
                    ;
                    if($speed>$fastestSpeed){
                        $fastestSpeed = $speed;
                        $fastestAstroidId = $astroid_data['id'];
                    }
                    
                    if($distance_from_earth<$closest_from_earth){
                        $closest_from_earth = $distance_from_earth;
                        $closestAstroidId = $astroid_data['id'];
                    }

                    $averageSize = ($averageSize+$avgSize) / 2;
                }
                if($keyone!=$s_date){
                    $count_range .= ', ';
                    $date_range .= ', ';
                }
                $count_range .= $count;
                $date_range .= "'".$keyone."'";
            }
            echo "Fastest Astroid : ".$fastestAstroidId.' - '.$fastestSpeed."<br>";
            echo "Closest Astroid from Earth: ".$closestAstroidId.' - '.$closest_from_earth."<br>";
            echo "Average Size of Astroids: ".$averageSize;

            /*$begin = new DateTime($s_date);
            $end = new DateTime($e_date);
            $interval = DateInterval::createFromDateString('1 day');
            $period = new DatePeriod($begin, $interval, $end);
            foreach ($period as $key=>$dt) {
                if($key!=0){
                    $date_range .= ', ';
                }
                $date_range .= "'".$dt->format("Y-m-d")."'";
            } */
        }
    ?></p>

    <div>
      <canvas id="myChart"></canvas>
    </div>
<?php 
        if($apidata != ''){ ?>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script>
  const ctx = document.getElementById('myChart');

  new Chart(ctx, {
    type: 'bar',
    data: {
      labels: [<?php echo $date_range; ?>],
      datasets: [{
        label: 'No of Astroid',
        data: [<?php echo $count_range; ?>],
        borderWidth: 1,
      borderColor: '#FF6384',
      backgroundColor: '#FFB1C1',
      }]
    },
    options: {
      scales: {
        y: {
          beginAtZero: true
        },

      }
    }
  });
</script>
<?php } ?>
</body>
</html>