<?php

    require_once '../vendor/autoload.php';

    use quete_upload\Controllers\DefaultController;

    $defaultController = new DefaultController();

    if (empty($_GET)) {
        echo $defaultController->indexAction();
    }
    elseif ($_GET['section'] === 'form') {
        echo $defaultController->formAction();
    }
    elseif ($_GET['section'] === 'success') {
        echo $defaultController->successAction();
    }
    elseif ($_GET['section'] === 'unlink') {
        echo $defaultController->unlinkAction();
    }