<?
foreach (getallheaders() as $nombre => $valor) {
    echo "$nombre: $valor <br>";

}

print_r($_POST);