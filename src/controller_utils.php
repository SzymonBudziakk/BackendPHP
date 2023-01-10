<?php

function get_messages() {
    if(isset($GLOBALS['msg'])) {
        $msg = $GLOBALS['msg'];
        $GLOBALS['msg'] = [];
        return $msg;
    }
    $GLOBALS['msg'] = [];
    return null;
}

function alert($message, $success = false) {
    // echo '<script>console.log("' . $message . '");</script>';
    array_push($GLOBALS['msg'], [
        'content' => $message,
        'success' => $success
    ]);
}

function uploadFile($watermark) {
    $valid = true;
    $file = $_FILES['forUpload'];
    $targetFile = basename($file["name"]);
    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));

    if(isset($_POST["submit"])) {
        $check = getimagesize($file["tmp_name"]);
        if($check !== false) {
            $valid = true;
        }
        else {
            alert("Załączony plik nie jest zdjęciem");
            $valid = false;
            return;
        }
    }

    if($fileExtension != "jpg" && $fileExtension != "png") {
        alert("Niepoprawne rozszerzenie pliku");
        $valid = false;
    }

    if ($file["size"] > 1000000) {
        alert("Rozmiar pliku jest większy niż 1MB");
        $valid = false;
    }

    if ($valid == false) {
        alert("Plik nie został wysłany");
    }
    else {
        if (move_uploaded_file($file["tmp_name"], $targetFile)) {
            alert("Plik został wysłany", true);
            $id = getImageId();
            createImages($targetFile, $id, $watermark);
            return $id . '.' . $fileExtension;
        }
        else {
            alert("Błąd podczas wysyłania pliku");
        }
    }
}

function isLoggedIn() {
    return isset($_SESSION['userId']);
}

function getPage(&$model) {
    if (!isset($_GET['page']) || !ctype_digit($_GET['page'])) {
        $model['current_page'] = 1;
    } else {
        $page = intval($_GET['page']);
        if (!isset($page) || $page < 1 || $page > $model['pages']) {
            $model['current_page'] = 1;
        } else {
            $model['current_page'] = $page;
        }
    }
}

function paging(&$model, $images, $imagesAmount) {
    $page = $model['current_page'];

    $startImage = ($page - 1) * $imagesAmount;
    $endImage = min($startImage + $imagesAmount, count($images));

    $model['images'] = [];
    for ($i = $startImage; $i < $endImage; $i++) {
        array_push($model['images'], $images[$i]);
    }
}