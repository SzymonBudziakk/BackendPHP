<?php

use MongoDB\BSON\ObjectID;

function createWatermark($file, $watermarkFile, $watermark, $fileExtension) {
    if($fileExtension == "jpg") {
        $image = imagecreatefromjpeg($file);
    }
    else {
        $image = imagecreatefrompng($file);
    }

    $stamp = imagecreatetruecolor(210, 140);
    imagefilledrectangle($stamp, 0, 0, 210, 140, 0x00FFFF);
    imagefilledrectangle($stamp, 10, 10, 200, 130, 0xFFFFFF);
    imagestring($stamp, 10, 20, 30, $watermark, 0x00FFFF);
    imagestring($stamp, 10, 20, 50, 'SzymonBudziak 2023', 0x00FFFF);
    
    imagecopymerge($image, $stamp, imagesx($image) - 220, imagesy($image) - 140 - 10, 0, 0, 210, 140, 70);

    if($fileExtension == "jpg") {
        imagejpeg($image, $watermarkFile);
    }
    else {
        imagepng($image, $watermarkFile);
    }
    imagedestroy($image);
}

function createMiniature($filePath, $miniatureFile, $fileExtension) {
    $width = getimagesize($filePath)[0];
    $height = getimagesize($filePath)[1];

    if($fileExtension == "jpg") {
        $primaryImage = imagecreatefromjpeg($filePath);
    }
    else {
        $primaryImage = imagecreatefrompng($filePath);
    }
    $miniatureImage = imagecreatetruecolor(200, 125);

    imagecopyresampled($miniatureImage, $primaryImage, 0, 0, 0, 0, 200, 125, $width, $height);
    
    if($fileExtension == "jpg") {
        imagejpeg($miniatureImage, $miniatureFile);
    }
    else {
        imagepng($miniatureImage, $miniatureFile);
    }
    imagedestroy($miniatureImage);
    imagedestroy($primaryImage);
}

function createImages($targetFile, $imageId, $watermarkText) {

    $fileExtension = strtolower(pathinfo($targetFile, PATHINFO_EXTENSION));
    $fileName = $imageId . "." . $fileExtension;

    $primaryFile = "images/original/" . $fileName;
    $miniature = "images/miniature/" . $fileName;
    $watermark = "images/watermark/" . $fileName;

    rename($targetFile, $primaryFile);
    createMiniature($primaryFile, $miniature, $fileExtension);
    createWatermark($primaryFile, $watermark, $watermarkText, $fileExtension);
}

function get_db() {
    $mongo = new MongoDB\Client(
        "mongodb://localhost:27017/wai", [
            'username' => 'wai_web',
            'password' => 'w@i_w3b',
        ]
    );
    
    $db = $mongo->wai;

    return $db;
}

function removeAllImages() {
    $db = get_db();
    $images = $db->images->find()->toArray();
    foreach($images as $image) {
        $db->images->deleteOne($image);
    }
}

function getImages() {
    $db = get_db();

    return $db->images->find()->toArray();
}

function addImage($image, $id = null) {
    $db = get_db();

    if ($id == null) {
        $db->images->insertOne($image);
    }
    else {
        $db->images->replaceOne(['_id' => new ObjectID($id)], $image);
    }
}

function getImageId() {
    $db = get_db();

    return count($db->images->find()->toArray()) + 1;
}

function getUser($login) {
    $db = get_db();

    $users = $db->users->find(['login' => $login])->toArray();

    if(count($users) == 0) return null;
    return $users[0];
}

function getUserById($id) {
    $db = get_db();

    $users = $db->users->find(['_id' => $id])->toArray();

    if(count($users) == 0) return null;
    return $users[0];
}

function AddUser($user, $id = null) {
    $db = get_db();

    if ($id == null) {
        $db->users->insertOne($user);
    }
    else {
        $db->users->replaceOne(['_id' => new ObjectID($id)], $user);
    }
}

function userExists($login) {
    return getUser($login) !== null;
}