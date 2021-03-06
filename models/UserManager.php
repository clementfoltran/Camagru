<?php 
    class UserManager extends Model {
        public function getUsers() {
            $this->getCo();
            return ($this->getAll('users', 'login'));
        }

        public function getUserId($login) {
            $query = "SELECT id_user FROM users WHERE login LIKE '$login'";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                return ($data['id_user']);
            }
            $req->closeCursor();
        }

        public function userExist($login) {
            $req = $this->getCo()->prepare("SELECT login FROM users WHERE LOGIN LIKE '$login'");
            $req->execute();
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                // A user with the same login found
                if (isset($data))
                    return (true);
            }
            $req->closeCursor();
            return (false);
        }

        public function addUser($firstname, $lastname, $login, $mail, $hash, $key) {
            $query = "INSERT INTO users VALUES(id_user, :firstname, :lastname, :login, :mail, :hash, :key, false, true)";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':firstname' => $firstname,
                ':lastname' => $lastname,
                ':login' => $login,
                ':mail' => $mail,
                ':hash' => $hash,
                ':key' => $key
            ]);
            $req->closeCursor();
        }

        public function login($login, $hash) {
            $query = "SELECT passwd, confirm FROM users WHERE login = :login";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':login' => $login
            ]);
            $data = $req->fetch(PDO::FETCH_ASSOC);
            if ($data['confirm'] == 0) {
                return (1);
            } else if ($data['passwd'] === $hash) {
                return (0);
            } else {
                return (2);
            }
            $req->closeCursor();
        }

        public function checkPassword($hash, $login) {
            $query = "SELECT * FROM users WHERE login LIKE :login AND passwd LIKE :hash";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':login' => $login,
                ':hash' => $hash
            ]);
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                if (isset($data))
                    return (true);
            }
            return (false);
            $req->closeCursor();
        }

        public function updatePassword($hash, $login) {
            $query = "UPDATE users SET passwd = :hash WHERE login LIKE :login";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':hash' => $hash,
                ':login' => $login
            ]);
            $req->closeCursor();
        }

        public function checkConfKey($key, $login) {
            $query = "SELECT * FROM users WHERE login LIKE :login AND confirmKey LIKE :key";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':login' => $login,
                ':key' => $key
            ]);
            while ($data = $req->fetch(PDO::FETCH_ASSOC)) {
                if (isset($data))
                    return (true);
            }
            return (false);
            $req->closeCursor();
        }

        public function updateConfKey($login) {
            $key = '';
            // Create the confirmation key for the mail
            for ($i = 0; $i < 14; $i++) {
                $key .= mt_rand(0, 9);
            }
            $query = "UPDATE users SET confirmKey = $key WHERE login = '$login'";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $req->closeCursor();
        }

        public function confirmAccount($login) {
            $query = "UPDATE users SET confirm = true WHERE login = '$login'";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $this->updateConfKey($login);
            $req->closeCursor();
        }

        public function updateLogin($login) {
            session_start();
            $old_login = $_SESSION['login'];
            $query = "UPDATE users SET login = :login WHERE login = '$old_login'";
            $req = $this->getCo()->prepare($query);
            $req->execute([':login' => $login]);
            $_SESSION['login'] = $login;
            $req->closeCursor();
        }

        public function getConfirmKey($email) {
            $query = "SELECT confirmKey FROM users WHERE email = '$email'";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            return ($data['confirmKey']);
            $req->closeCursor();
        }

        public function resetPasswd($hash, $key, $newKey) {
            $query = "UPDATE users SET passwd = '$hash', confirmKey = $newKey WHERE confirmKey = :key";
            $req = $this->getCo()->prepare($query);
            $req->execute([':key' => $key]);
            $req->closeCursor();
        }

        public function getLoginById($id_user) {
            $query = "SELECT login FROM users WHERE id_user LIKE '$id_user'";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            $req->closeCursor();
            return ($data['login']);
        }

        public function getMailById($id_user) {
            $query = "SELECT email FROM users WHERE id_user LIKE $id_user";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            $req->closeCursor();
            return ($data['email']);
        }

        public function getNotif($id_user) {
            $query = "SELECT notif FROM users WHERE id_user = $id_user";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            $req->closeCursor();
            return ($data['notif']);
        }

        public function setNotif($id_user, $notif) {
            $query = "UPDATE users SET notif = $notif WHERE id_user = :id_user";
            $req = $this->getCo()->prepare($query);
            $req->execute([':id_user' => $id_user]);
            $req->closeCursor();
        }

        public function dropUser($id_user) {
            $req = $this->getCo()->prepare("DELETE FROM likes WHERE id_user = $id_user");
            $req->execute();
            $req = $this->getCo()->prepare("DELETE FROM comments WHERE id_user = $id_user");
            $req->execute();
            $req = $this->getCo()->prepare("DELETE FROM photos WHERE id_user = $id_user");
            $req->execute();
            $req = $this->getCo()->prepare("DELETE FROM users WHERE id_user = $id_user");
            $req->execute();
            $req->closeCursor();
        }

        public function getNotifByPhotoOwner($id_photo)
        {
            $query = "SELECT notif FROM users AS U
                     INNER JOIN photos AS P ON U.id_user=P.id_user
                     WHERE id_photo = $id_photo";
            $req = $this->getCo()->prepare($query);
            $req->execute();
            $data = $req->fetch(PDO::FETCH_ASSOC);
            return ($data['notif']);
            $req->closeCursor();
        }

        public function updateMailById($id_user, $mail) {
            $query = "UPDATE users SET email = :mail WHERE id_user = :id_user";
            $req = $this->getCo()->prepare($query);
            $req->execute([
                ':mail' => $mail,
                ':id_user' => $id_user
            ]);
            $req->closeCursor();
        }
    }
?>
