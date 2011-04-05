<?php
/**
 * TournamentMatrix2 - A dominance-directed graph implemented as a matrix.
 * See http://aix1.uottawa.ca/~jkhoury/graph.htm.
 */
class TournamentMatrix2 {
    private $tournamentArray, $powers;

    public function getArray() { return $this->tournamentArray; }
    public function setArray($array) { $this->tournamentArray = $array; }
    public function getPowers() { return $this->powers; }
    public function setPowers($array) { $this->powers = $array; }

    /**
     * Compute the power matrix (A=M+M^2) and sort teams.
     */
    public function computePowers() {

        // Build a numeric-index array ($matrix) for matrix operations:
        $teams = $this->getArray();
        $matrix = array_values($teams);
        for ($i=0; $i<count($matrix); $i++)
            $matrix[$i] = array_values($matrix[$i]);

        // Compute the power matrix (A=M+M^2):
        $result = $this->multiply($matrix, $matrix);
        $result = $this->add($matrix, $result);
        $result = $this->sumRows($result);
        
        // Sort by highest power first, associate with team names,
        // and store the result in $powers:
        foreach ($result as $index=>$power) {
            $powers[key($teams)] = $power;
            next($teams);
        }
        arsort($powers);
        $this->setPowers($powers);
    }

    /**
     * Parse scores and build the teams win/loss matrix (assoc array).
     * Ignore comment lines (#) and blank lines.  Parse lines in this format:
     *     TeamA Score vs/@ TeamB Score
     * Sample: http://derekwilliams.us/fragments/nflscores.txt.
     */
    public function parseScores($scores) {
       // Build $teams as an associative array by team name:
        $teams = array();
        foreach (explode("\n", $scores) as $line) {
          $words = preg_split("/[\s]+/", $line);
          if (count($words) > 5 && !ereg('^#', $line)) {
            $winner = null;
            if ($words[2] > $words[5]) {
                $winner = $words[1];    $loser = $words[4];
            } elseif ($words[5] > $words[2]) {
                $winner = $words[4];    $loser = $words[1];
            }
            if (!is_null($winner)) {
                if (is_null($team_array = $teams[$winner]))
                    $team_array = array();
                $team_array[$loser] = 1;
                $teams[$winner] = $team_array;

                 if (is_null($team_array = $teams[$loser]))
                    $team_array = array();
                $team_array[$winner] = 0;
                $teams[$loser] = $team_array;
            }
          }
        }
        $this->setArray($teams);
    }

    // Multiply two matrix arrays:
    private function multiply($array1, $array2) {
        for ($i=0; $i<count($array1); $i++)
            for ($j=0; $j<count($array1[0]); $j++) {
                $result[$i][$j] = 0;
                for ($k=0; $k<count($array1); $k++)
                    $result[$i][$j] += $array1[$i][$k] * $array2[$k][$j];
            }
        return $result;
    }

    // Add two matrix arrays:
    private function add($array1, $array2) {
        for ($i=0; $i<count($array1); $i++)
            for ($j=0; $j<count($array1[0]); $j++)
                $result[$i][$j] = $array1[$i][$j] + $array2[$i][$j];
        return $result;
    }

    // Sum (horizontally) the rows of a matrix array:
    private function sumRows($array) {
        for ($i=0; $i<count($array); $i++)
            for ($j=0; $j<count($array[0]); $j++)
                $result[$i] += $array[$i][$j];
        return $result;
    }
}
?>
