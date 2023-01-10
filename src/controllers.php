<?php

require_once 'business.php';
require_once 'controller_utils.php';

function upload(&$model) {
    $model['title'] = 'Dodaj zdjęcie';

    if (isLoggedIn()) {
        $user = getUserById($_SESSION['userId']);
        $model['author'] = $user['login'];
    }
    else {
        $model['author'] = '';
    }

    if (isset($_FILES['forUpload']['name'])) {
        $fileName = uploadFile($_POST['watermark']);
    }
    $model['messages'] = get_messages();

    if (!isset($_FILES['forUpload']['name'])) {
        return 'upload_view';
    }

    $image = [
        'fileName' => $fileName,
        'title' => $_POST['title'],
        'author' => $_POST['author'],
        'ImageId' => getImageId()
    ];

    addImage($image);

    return 'upload_view';
}

function gallery(&$model) {
    $model['title'] = 'Galeria';

    //removeAllImages();
    $imageAmount = 2;
    $images = getImages();
    $model['pages'] = (count($images) - 1) / $imageAmount + 1;

    getPage($model);
    paging($model, $images, $imageAmount);

    return 'gallery_view';
}

function collection(&$model) {
    $model['title'] = 'Kolekcja';

    if (!isset($_SESSION['images'])) {
        return 'redirect:gallery';
    }

    $imageAmount = 2;

    $images = $_SESSION['images'];
    $model['pages'] = (count($images) - 1) / $imageAmount + 1;

    getPage($model);

    paging($model, $images, $imageAmount);

    return 'collection_view';
}

function collection_add(&$model) {
    $model['title'] = 'Kolekcja';

    // dodawanie do kolekcji
    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $images = getImages();
        for($i = 1; $i <= count($images); $i++) {
            $image = $images[$i - 1];
            if(isset($_POST['checkbox_' . $i])) {
                if(in_array($image, $_SESSION['images'])) {
                    alert('Obraz jest już zapisany');
                }
                else {
                    array_push($_SESSION['images'], $image);
                }
            }
        }
    }

    return 'redirect:collection';
}

function collection_del(&$model) {
    $model['title'] = 'Kolekcja';

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $images = [];
        foreach($_SESSION['images'] as $image) {
            if(!isset($_POST['checkbox_' . $image['ImageId']])) {
                array_push($images, $image);
            }
        }
        $_SESSION['images'] = $images;
    }

    return 'redirect:collection';
}

function login(&$model) {
    $model['title'] = 'Logowanie';

    if (isLoggedIn()) {
        return 'redirect:logged_in';
    }

    $model['messages'] = get_messages();

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $login = $_POST['login'];
        $password = $_POST['password'];

        $valid_login = true;

        if (!userExists($login)) {
            alert('Użytkownik ' . $login . ' nie istnieje');
            $valid_login = false;
        }

        if (!$valid_login) {
            $model['messages'] = get_messages();
            return 'login_view';
        }

        $user = getUser($login);
        $model['login'] = $login;
        $hashedPassword = $user['password'];
        $id = $user['_id'];

        if (!password_verify($password, $hashedPassword)) {
            alert('Nieprawidłowe hasło');
            $valid_login = false;
        }

        if (!$valid_login) {
            $model['messages'] = get_messages();
            return 'login_view';
        }

        // Start session
        $_SESSION['userId'] = $id;

        return 'redirect:logged_in';
    }
    return 'login_view';
}

function register(&$model) {
    $model['title'] = 'Rejestracja';

    if (isLoggedIn()) {
        return 'redirect:logged_in';
    }

    if ($_SERVER['REQUEST_METHOD'] === 'POST') {
        $email = $_POST['email'];
        $login = $_POST['login'];
        $password = $_POST['password'];
        $password2 = $_POST['password2'];
        $valid = true;

        if ($password !== $password2) {
            alert('Hasła są inne');
            $valid = false;
        }

        if (userExists($login)) {
            alert('Użytkownik ' . $login . ' już istnieje');
            $valid = false;
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            alert('Niepoprawny adres email');
            $valid = false;
        }

        if (!$valid) {
            $model['messages'] = get_messages();
            return 'register_view';
        }

        $hashedPassword = password_hash($password, PASSWORD_DEFAULT);

        $user = [
            'email' => $email,
            'login' => $login,
            'password' => $hashedPassword
        ];

        AddUser($user);

        alert('Jesteś zarejestrowany');

        return 'redirect:login';
    }
    return 'register_view';
}

function logout(&$model) {
    $model['title'] = 'Wylogowanie';
    if (isLoggedIn()) {
        session_destroy();
    }
    return 'redirect:logged_in';
}

function logged_in(&$model) {
    $model['title'] = 'Zalogowany';

    if (isset($_SESSION['userId'])) {
        $user = getUserById($_SESSION['userId']);
        $model['user'] = $user;
        return 'logged_in_view';
    } else {
        return 'not_logged_in_view';
    }
}