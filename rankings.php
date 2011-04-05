<?php
    require 'TournamentMatrix.php';
    if (isset($_POST['submit'])) {
        doTourney($_POST['scores']);
    }

    function doTourney($scores) {
        $matrix = new TournamentMatrix2();
        $matrix->parseScores($scores);
        $matrix->computePowers();
        show_powers($matrix->getPowers());
    }

    function show_powers($powers) {
        echo '<table border="1">';
        echo '<th>Rank</th><th>Team</th><th>Power</th>';
        $i=1;
        foreach ($powers as $name=>$power) {
            echo '<tr border="1">';
            printf('<td border="1">%d</td>',$i++);
            printf('<td border="1">%s</td>',$name);
            printf('<td border="1">%s</td>',$power);
            echo '</tr>';
        }
        echo '</table>';
    }
?>
