<!doctype html>
<html lang="fr">
<head>
  <meta charset="utf-8">
  <title>Demographics stuff</title>

    <style type="text/css">

        body {
            font-family: Arial, serif;
            margin: auto;
            <?php if (!$this->has('demographics')): ?>
                max-width: 600px;
            <?php endif; ?>
        }
        fieldset {
            border: 1px solid gray;
            border-radius: 10px;
            padding: 15px;
            margin: 25px;
        }

        legend {
            text-align: right;
            font-size: large;
            font-weight: bold;
        }
    </style>

</head>
<body>
    <?php
    if (!$this->has('demographics')):
        if ($this->has('files')):
            $action = !$this->has('cities') ? 'displayCities' : 'displayChart';
            ?>
            <fieldset>
                <legend>Demography</legend>
                <form action="./?action=<?php echo $action; ?>" method="post">
                    <h4>1. Select an input file</h4>
                    <select name="file">
                        <?php foreach ($this->get('files') as $file): ?>
                            <option value="<?php echo $file; ?>"><?php echo $file; ?></option>
                        <?php endforeach; ?>
                    </select>

                    <?php if ($this->has('cities')): ?>
                        <h4>2. Select one or several cities</h4>
                        <select name="cities[]" multiple="multiple" size="15" style="padding: 5px;">
                            <?php foreach ($this->get('cities') as $city): ?>
                                <option value="<?php echo $city; ?>"><?php echo $city; ?></option>
                            <?php endforeach; ?>
                        </select>
                    <?php endif; ?>

                    <div align="right">
                        <input type="submit" value="Compute">
                    </div>
                </form>
            </fieldset>
        <?php else: ?>
            <div style="text-align: center; border: 1px solid darkred; color: darkred; padding: 10px;">
                No xml candidate file has been found on directory "<?php echo $this->get('directory'); ?>".
            </div>
        <?php endif; ?>
    <?php else: ?>

        <div class="chart-container" style="position: relative; height:45vh; width:90vw; margin: auto;">
            <canvas id="demographics"></canvas>
            <div style="text-align: center; margin-top: 30px;">
                <a href="./">Back to home</a>
            </div>
        </div>

        <script type="text/javascript">
            var config = {
                type: 'line',
                data: {
                    labels: [<?php echo '\'' . implode($this->get('years'), '\',\'') . '\''; ?>],
                    datasets: [
                        <?php
                        $index = 0;
                        foreach ($this->get('demographics') as $city => $demographics):
                            $curveColor = $this->get('colors')[$index];
                            $color = 'rgba(' . $curveColor[0] . ',' . $curveColor[1] . ',' . $curveColor[2] . ',1)';
                        ?>
                            {
                                label: '<?php echo $city; ?>',
                                fill: false,
                                borderColor: '<?php echo $color; ?>',
                                backgroundColor: '<?php echo $color; ?>',
                                data: [ <?php echo implode(array_values($demographics), ','); ?> ]
                            },
                        <?php
                        $index++;
                        endforeach;
                        ?>
                    ]
                },
                options: {
                    responsive: true,
                    title:{
                        display:true,
                        text: "<?php echo count($this->get('demographics')) > 1 ? 'Cities demography' : key($this->get('demographics')) . '\'s demography'; ?>"
                    },
                    tooltips: {
                        mode: 'index',
                        intersect: false,
                    },
                    hover: {
                        mode: 'nearest',
                        intersect: true
                    },
                    scales: {
                        xAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Years'
                            }
                        }],
                        yAxes: [{
                            display: true,
                            scaleLabel: {
                                display: true,
                                labelString: 'Population'
                            }
                        }]
                    }
                }
            };

            window.onload = function() {
                var ctx = document.getElementById('demographics').getContext("2d");
                window.demographicsChart = new Chart(ctx, config);
            };
        </script>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js" type="text/javascript"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.bundle.min.js" type="text/javascript"></script>
    <?php endif; ?>

</body>
</html>
