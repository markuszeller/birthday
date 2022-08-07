<?php
$events = require_once './src/events.php';
require_once './src/functions.php';

$currentYear = date("Y");
$today       = new DateTime();
$todayDate   = date("Y-m-d");
?>
    <html lang="de">
    <head>
        <link href="css/style.css" rel="stylesheet">
        <title>Geburtstage</title>
    </head>
    <body>
    <table>
        <?php
        $birthDays  = [];
        $birthTexts = [];
        foreach ($events as $title => $event) {
            $year = $currentYear;
            $css  = "";

            [$day, $month] = $event;
            $eventDate = sprintf("%d-%02d-%02d", $year, $month, $day);

            $sameDay = $eventDate === $todayDate;
            if ($eventDate < $todayDate) {
                $year++;
                $eventDate = sprintf("%d-%02d-%02d", $year, $month, $day);
                $css       = "nextyear";
            }

            try {
                $ev = new DateTime($eventDate);
            }
            catch (Exception $e) {
                die($e->getMessage());
            }
            $diff             = $ev->diff($today);
            $daysRemaining    = $diff->format("%a");
            $hoursRemaining   = $diff->format("%h");
            $minutesRemaining = $diff->format("%i");
            if ($daysRemaining < 31) $css .= " strong";
            [$y, $m, $d] = explode("-", $eventDate);
            $eventDate   = sprintf("%02d.%02d.%d", $d, $m, $y);
            $birthDays[] = (int) $daysRemaining;
            if (!$sameDay) {
                $daysRemainingText = "noch $daysRemaining " . pluralize("Tag", "Tage", $daysRemaining);
                $daysRemainingText .= " $hoursRemaining " . pluralize("Stunde", "Stunden", $hoursRemaining);
                $daysRemainingText .= " und $minutesRemaining " . pluralize("Minute", "Minuten", $minutesRemaining);
            } else {
                $daysRemainingText = 'heute';
            }
            $birthTexts[] = sprintf('<tr class="%s"><td>%s</td><td>%s</td><td>%s</td></tr>', $css, $title, $eventDate, $daysRemainingText);
        }

        array_multisort(
            $birthDays, SORT_ASC, SORT_NUMERIC,
            $birthTexts, SORT_ASC, SORT_STRING
        );

        echo implode(PHP_EOL, $birthTexts);
        ?>

    </table>
    </body>
    </html>
<?php
