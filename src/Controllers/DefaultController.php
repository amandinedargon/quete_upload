<?php

    namespace quete_upload\Controllers;

    /**
     * Class DefaultController
     * @package UploadFichier\Controllers
     */
    class DefaultController extends Controller
    {

        public function indexAction()
        {
            $dirname = "../uploads/";
            $image = glob($dirname . "*.{jpeg,jpg,gif,png}", GLOB_BRACE);
            return $this->twig->render('home.html.twig', array(
                "files" => $image
            ));
        }

        public function formAction()
        {
            if (empty($_FILES)) {
                return $this->twig->render('form.html.twig');
            }
        }

        public function unlinkAction()
        {
            $f = $_GET['file'];
            if (file_exists($f)) unlink($f);
            header('Location: index.php');
        }

        public function successAction()
        {

            if (!empty($_FILES['upload']['name'][0])) {
                $files = $_FILES['upload'];
                $failed = array();
                $uploaded = array();
                $allowed = array('jpg', 'png', 'gif', 'jpeg');

                foreach ($files['name'] as $position => $file_name) {

                    $file_tmp = $files['tmp_name'][$position];
                    $file_size = $files['size'][$position];
                    $file_error = $files['error'][$position];

                    $file_ext = explode('.', $file_name);
                    $file_ext = strtolower(end($file_ext));

                    if (in_array($file_ext, $allowed)) {

                        if ($file_error === 0) {

                            if ($file_size <= 1000000) {

                                $file_name_new = "image" . uniqid('', true) . '.' . $file_ext;
                                $file_destination = '../uploads/' . $file_name_new;

                                if (move_uploaded_file($file_tmp, $file_destination)) {
                                    $uploaded[$position] = $file_destination;
                                } else {
                                    $failed[$position] = "[{$file_name}] failed to upload.";
                                }

                            } else {
                                $failed[$position] = "[{$file_name}] is too large.";
                            }

                        } else {
                            $failed[$position] = "[{$file_name}] errored with code {$file_error}.";
                        }
                    } else {
                        $failed[$position] = "[{$file_name}] file extension '{file_ext}' is not allowed.";
                    }
                }

                if (!empty($uploaded)) {
                    return $this->twig->render('success.html.twig', array(
                        'files' => $uploaded,
                    ));

                }
                if (!empty($failed)) {
                    print_r($failed);
                }

            }


        }


    }