<?php

    require_once "models/Message.php";
    require_once "models/User.php";
    require_once "dao/UserDAO.php";
    require_once "globals.php";
    require_once "db.php";

    $message = new Message($BASE_URL);

    $userDao = new UserDAO($conn, $BASE_URL);

    // Resgata tipo do formulário
    $type = filter_input(INPUT_POST, "type");

    // Verifica tipo do formulário
    if($type === "register") {

        $name = filter_input(INPUT_POST, "name");
        $lastname = filter_input(INPUT_POST, "lastname");
        $email = filter_input(INPUT_POST, "email");
        $password = filter_input(INPUT_POST, "password");
        $confirmpassword = filter_input(INPUT_POST, "confirmpassword");

        // Verificação de dados mínimos
        if($name && $lastname && $email && $password) {

            // Verificar se as senhas são iguais
            if($password === $confirmpassword) {

                // Verificar se o e-mail já está cadastrado
                if($userDao->findByEmail($email) === false) {

                    

                } else {

                    // Email já cadastrado
                    $message->setMessage("Usuário já cadastrado, tente outro e-mail.", "error", "back");
                }
                
            } else {

                // Mensagem para senhas diferentes
                $message->setMessage("As senhas não são iguais.", "error", "back");
            }

        } else {

            // Enviar uma mensagem de erro de dados faltantes
            $message->setMessage("Por favor, preencha todos os campos.", "error", "back");
        }

    } else if($type === "login"){
        
    }