<?php
    require_once('views/View.php');
    
    class ControllerHome {
        private $_photosManager;
        private $_view;

        public function __construct($url) {
            if (isset($url) && count($url) > 1) {
                throw new Exception('Page not found');
            } else if ($_GET['submit'] === 'like') {
                $this->_like();
            } else if ($_GET['submit'] === 'page') {
                $this->photos();
            } else {
                $this->_limit = 6;
                $this->photos();
            }
        }

        private function photos() {
            $this->_photosManager = new PhotoManager;
            $limit = intval(($_GET['n'] + 1) * 6);
            $pages = ceil($this->_photosManager->countPhotos() / 6);
            $this->_view = new View('Home');
            $this->_view->generate(array(
                'photos' => $this->_photosManager->getAllPhotos($limit),
                'page' => ($limit < $pages * 6) ? $limit / 6 : $pages,
                'tpages' => $pages
            ));
        }

        private function _like() {
            session_start();
            $id_photo = $_POST['idPhoto'];
            $id_user = $_SESSION['id'];
            if ($_SESSION['login'] === null) {
                $this->_view = new View('Login');
                $this->_view->generate(array("err" => "You must be connected to like a photo"));
            } else {
                $this->_photosManager = new PhotoManager;
                if ($this->_photosManager->alreadyLike($id_photo, $id_user) === false) {
                    $this->_photosManager->like($id_photo, $id_user);
                    echo 1;
                } else {
                    $this->_photosManager->unlike($id_photo, $id_user);
                    echo -1;
                }
            }
        }
    }
?>