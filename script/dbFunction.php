<?php
/**
 * Created by IntelliJ IDEA.
 * User: skohlrus
 * Date: 11.01.2018
 * Time: 15:46
 */
setcookie("test", "test", 3600);
session_start();

//Hier sind alle Datenbankfunktionen hinterlegt

class dbFunction
{
    function __construct($dbConnection)
    {
        $this->dbCon = $dbConnection;

    }
//	Die E-Mail muss einem E-Mail Regex entsprechen
//	Passwort: mindestens 8 Zeichen, ein Kleinbuchstabe, ein Großbuchstabe eine Zahl und ein Sonderzeichen
    public $emailRegex = "";
    public $pwRegex = "";

    public function userLogin($username, $password)
    {
        $statement = $this->dbCon->prepare("SELECT * FROM tab_user WHERE userName = :userName");
        $result = $statement->execute(array('userName' => $username));
        $user = $statement->fetch();

        if ($user !== false && password_verify($password, $user['password'])) {
            $_SESSION['userId'] = $user['userId'];
            $_SESSION['userName'] = $user['userName'];
            $sessionId = session_id();
            setcookie("username", "$username", false, "/", false);
            setcookie("sessionId", "$sessionId", false, "/", false);
            header("location: ../public/index.html");
        } else {
            header("location: ../public/login.html#wrong-login");
        }
    }

    public function userRegister($userName, $firstName, $lastName, $email, $password, $address, $birthday, $gender, $postalCode)
    {
        $registerStatement = $this->dbCon->prepare("INSERT INTO tab_user(userName,firstName,lastname,password, email, address, birthday, genderId, postalcode) VALUES (:userName,:firstName,:lastName,:password,:email,:address,:birthday,:genderId,:postalCode)");
        if ($this->checkMail($email) && $this->checkUserName($userName)) {
            $genderFetch = $this->getGenderId($gender);
            $genderId = $genderFetch["genderId"];
            $formattedBirthday = strtotime($birthday);
            $formattedBirthday = date("Y-m-d H:i:s", $formattedBirthday);
            $hashedPassword = password_hash($password, PASSWORD_DEFAULT);
            $registerStatement->bindParam(':userName', $userName);
            $registerStatement->bindParam(':firstName', $firstName);
            $registerStatement->bindParam(':lastName', $lastName);
            $registerStatement->bindParam(':password', $hashedPassword);
            $registerStatement->bindParam(':email', $email);
            $registerStatement->bindParam(':address', $address);
            $registerStatement->bindParam(':birthday', $formattedBirthday);
            $registerStatement->bindParam(':genderId', $genderId);
            $registerStatement->bindParam(':postalCode', $postalCode);
            $registerResult = $registerStatement->execute();
            mkdir("../userData/" . $userName, 0777);
            $this->userLogin($userName, $password);
        } else {
            return false;
        }
    }

    public function getGenderId($gender)
    {
        $genderIdStatement = $this->dbCon->prepare("SELECT genderId FROM tab_gender WHERE gender = :gender");
        $genderIdStatement->bindParam(':gender', $gender);
        $genderIdResult = $genderIdStatement->execute();
        return $genderIdStatement->fetch($genderIdResult);
    }

    public function checkMail($email)
    {
        $checkMailStatement = $this->dbCon->prepare("SELECT * FROM tab_user where email = :mail");
        $checkMailStatement->bindParam(':mail', $email);
        $checkMailResult = $checkMailStatement->execute();
        $checkMailFetch = $checkMailStatement->fetch($checkMailResult);
        if (!$checkMailFetch) {
            return true;
        } else {
            return false;
        }
    }

    public function checkUserName($userName)
    {
        $checkUserNameStatement = $this->dbCon->prepare("SELECT * FROM tab_user where userName = :userName");
        $checkUserNameStatement->bindParam(':userName', $userName);
        $checkUserNameResult = $checkUserNameStatement->execute();
        $checkUserNameFetch = $checkUserNameStatement->fetch($checkUserNameResult);
        if (!$checkUserNameFetch) {
            return true;
        } else {
            return false;
        }
    }

    public function getGenderData($actualGender)
    {
        $options = "";
        $statement = $this->dbCon->prepare("SELECT gender FROM tab_gender");
        $result = $statement->execute();

        while ($row = $statement->fetch($result)) {
            if ($actualGender !== $row['gender']) {
                $options = $options . "<option>" . $row['gender'] . "</option>";
            }
        }
        return $options;
    }

    public function addGender($gender)
    {
        $addGenderStatement = $this->dbCon->prepare("INSERT INTO tab_gender(gender) VALUES (:gender)");
        if ($this->checkGenderName($gender)) {
            $addGenderStatement->bindParam(':gender', $gender);
            $addGenderResult = $addGenderStatement->execute();
            header("Refresh:0; url=../public/register.php");
        }
    }

    public function checkGenderName($gender)
    {
        $checkGenderStatement = $this->dbCon->prepare("SELECT * FROM tab_gender where gender = :gender");
        $checkGenderStatement->bindParam(':gender', $gender);
        $checkGenderResult = $checkGenderStatement->execute();
        $checkGenderFetch = $checkGenderStatement->fetch($checkGenderResult);
        if (!$checkGenderFetch) {
            return true;
        } else {
            return false;
        }
    }

    public function getGenderById($genderId)
    {
        $getGenderIdStatement = $this->dbCon->prepare("SELECT gender FROM tab_gender where genderId = :genderId");
        $getGenderIdStatement->bindParam(':genderId', $genderId);
        $getGenderIdResult = $getGenderIdStatement->execute();
        $getGenderIdFetch = $getGenderIdStatement->fetch($getGenderIdResult);
        return $getGenderIdFetch['gender'];
    }

    public function getUserDataForSettings()
    {
        $userId = $_SESSION['userId'];
        $getUserDataStatement = $this->dbCon->prepare("SELECT firstName,lastName,email,address,postalcode,genderId from tab_user where userId = :userId");
        $getUserDataStatement->bindParam(':userId', $userId);
        $getUserDataResult = $getUserDataStatement->execute();
        $checkGenderFetch = $getUserDataStatement->fetch($getUserDataResult);
        return $checkGenderFetch;
    }

    public function updateUserData($oldPassword,$firstName,$lastName,$email,$address,$postalCode,$gender)
    {
        $userId = $_SESSION['userId'];
        $statement = $this->dbCon->prepare("SELECT * FROM tab_user WHERE userId = :userId");
        $updateUserDataStatement = $this->dbCon->prepare("UPDATE tab_user SET firstName = :firstName, lastName = :lastName,email = :email, address = :address,postalcode = :postalCode, genderId = :genderId WHERE userId = :userId");
        $result = $statement->execute(array('userId' => $userId));
        $user = $statement->fetch();
        if ($user !== false && password_verify($oldPassword, $user['password'])) {
            $genderFetch = $this->getGenderId($gender);
            $genderId = $genderFetch["genderId"];
            $updateUserDataStatement->bindParam(':userId', $userId);
            $updateUserDataStatement->bindParam(':firstName', $firstName);
            $updateUserDataStatement->bindParam(':lastName', $lastName);
            $updateUserDataStatement->bindParam(':email', $email);
            $updateUserDataStatement->bindParam(':address', $address);
            $updateUserDataStatement->bindParam(':genderId', $genderId);
            $updateUserDataStatement->bindParam(':postalCode', $postalCode);
            $updateUserDataResult = $updateUserDataStatement->execute();
            header("location: ../public/index.html");
        }
    }

    public function updatePassword($oldPassword,$newPassword) {
        $userId = $_SESSION['userId'];
        $statement = $this->dbCon->prepare("SELECT * FROM tab_user WHERE userId = :userId");
        $updatePasswordStatement = $this->dbCon->prepare("UPDATE tab_user SET password = :password WHERE userId = :userId");
        $result = $statement->execute(array('userId' => $userId));
        $user = $statement->fetch();
        if ($user !== false && password_verify($oldPassword, $user['password'])) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updatePasswordStatement->bindParam(':userId', $userId);
            $updatePasswordStatement->bindParam(':password', $hashedPassword);
            $updatePasswordResult = $updatePasswordStatement->execute();
            header("location: ../public/index.html");
        }
    }
}