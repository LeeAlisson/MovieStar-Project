<?php

require_once "models/Message.php";
require_once "models/User.php";
require_once "dao/UserDAO.php";
require_once "globals.php";
require_once "db.php";

$message = new Message($BASE_URL);
$userDao = new UserDAO($conn, $BASE_URL);
$user = new User();

// Resgata tipo do formulário
$type = filter_input(INPUT_POST, "type");

// Atualizar usuário
if ($type == "update") {

    // Resgata dados do usuário
    $userData = $userDao->verifyToken();

    // Receber dados do POST
    $name = filter_input(INPUT_POST, "name");
    $lastname = filter_input(INPUT_POST, "lastname");
    $email = filter_input(INPUT_POST, "email");
    $bio = filter_input(INPUT_POST, "bio");

    //Preencher dados do usuário
    $userData->name = $name;
    $userData->lastname = $lastname;
    $userData->email = $email;
    $userData->bio = $bio;

    $defaultImage = "user.png";

    // Upload da imagem
    if (isset($_FILES["image"]) && !empty($_FILES["image"]["tmp_name"])) {

        $image = $_FILES["image"];
        $imageTypes = ["image/jpeg", "image/jpg", "image/png"];
        $jpgArray = ["image/jpeg", "image/jpg"];

        // Verifique se o usuário já possui uma imagem e deleta a imagem antiga, se não for a padrão
        if ($userData->image != $defaultImage && file_exists("./img/users/" . $userData->image)) {
            unlink("./img/users/" . $userData->image); // Exclui o arquivo antigo, se não for a imagem padrão
        }

        // Chegagem de tipo de imagem
        if (in_array($image["type"], $imageTypes)) {

            // Imagem JPG
            if (in_array($image["type"], $jpgArray)) {
                $imageFile = imagecreatefromjpeg($image["tmp_name"]);

                // Imagem PNG
            } else {
                $imageFile = imagecreatefrompng($image["tmp_name"]);
            }

            $imageName = $user->imageGenerateName();

            imagejpeg($imageFile, "./img/users/" . $imageName, 100);

            $userData->image = $imageName;

        } else {
            $message->setMessage("Tipo inválido de imagem!", "error", "back");
        }

    }

    $userDao->update($userData);

    // Atualizar senha do usuário
} else if ($type == "changepassword") {

    // Receber dados do POST
    $password = filter_input(INPUT_POST, "password");
    $confirmpassword = filter_input(INPUT_POST, "confirmpassword");
    $userData = $userDao->verifyToken();
    $id = $userData->id;

    if($password == $confirmpassword) {

        $finalPassword = $user->generatePassword($password);

        $user->password = $finalPassword;
        $user->id = $id;

        $userDao->changePassword($user);

    } else {
        $message->setMessage("As senhas não são iguais!", "error", "back");
    }


} else {
    $message->setMessage("Informações inválidas!", "error", "index.php");
}