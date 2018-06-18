<?php
require_once __DIR__ . '/vendor/autoload.php';
setlocale(LC_ALL, 'sk_SK.UTF-8');

$stylesheet = '@page { margin: 0.3cm 0.5cm; } body {font-size: 8px;} h1 {font-size: 15px; padding: 3px auto; } table, tr, td {border: 1px solid black; border-collapse: collapse; border-spacing: 0; font-size: 8px;} td { padding: 0.4cm 0.2cm; }';
$lines = file('locations.tsv');

$times = array('7:00-7:15', '7:15-7:30', '7:30-7:45', '7:45-8:00', '8:00-8:15', '8:15-8:30', '8:30-8:45', '8:45-9:00');
foreach ($lines as $key => $line) {
    // skip header
    if ($key == 0) {
        continue;
    }
    $parts = explode("\t", $line);
    if (trim($parts[0]) == '') {
        break;
    }
    $doc = '';
    $mpdf = new \Mpdf\Mpdf(['mode' => 'utf-8', 'format' => 'A4-L', 'bleedMargin' => 1, 'default_font_size' => 10]);
    $doc .= '<h1>Sčítanie cyklistov, lokalita ' . $parts[0] . ' - ' . $parts[1] . ' (organizuje <img src="cyklokoalicia_logo.svg" height="20px">, info@cyklokoalicia.sk)</h1>';
    $normalized_loc1 = preg_replace('/[^A-Za-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($parts[0])));
    $normalized_loc2 = preg_replace('/[^A-Za-z0-9]/', '', iconv('UTF-8', 'ASCII//TRANSLIT', trim($parts[1])));
    $normalized = strtolower($normalized_loc1) . '-' . strtolower($normalized_loc2) . '.pdf';
    $directions = array();
    for ($i = 4; $i <= 50; $i++) {
        if (isset($parts[$i]) and trim($parts[$i])) {
            $directions[] = $parts[$i];
        } else {
            break;
        }
    }
    $doc .= 'Odporúčané miesto: ' . $parts[3] . '&nbsp;&nbsp; Dátum: ..........................&nbsp;&nbsp; Meno sčítavajúceho: ...............................';
    $doc .= '<table width="100%"><tr><td width="18%">Smer z</td><td width="18%">Smer do</td>';
    foreach ($times as $time) {
        $doc .= '<td width="8%">' . $time . '</td>';
    }
    $doc .= '</tr>';
    foreach ($directions as $keyfrom => $from) {
        $doc .= '<tr><td><strong>' . chr(65 + $keyfrom) . ' &nbsp; ' . $from . '</strong> →→→ </td>';
        $passed = false;
        foreach ($directions as $keyto => $to) {
            if ($keyfrom != $keyto) {
                if ($passed == true) {
                    $doc .= '<tr><td></td>';
                }
                $doc .= '<td>' . chr(65 + $keyto) . ' &nbsp; ' . $to . '</td>';
                $doc .= '<td></td><td></td><td></td><td></td><td></td><td></td><td></td><td></td></tr>' . "\n";
                $passed = true;
            }
        }
    }
    $doc .= '</table>';
    $mpdf->WriteHTML($stylesheet, 1);
    $mpdf->WriteHTML($doc, 2);
    $mpdf->Output('pdf/' . $normalized, 'F');
    echo 'Generated ' . $normalized . '<br>';
    flush();
    ob_flush();
}
