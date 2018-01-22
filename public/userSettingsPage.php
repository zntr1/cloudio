<?php
require_once '../script/dbConfig.php';
$userData = $dbFunction->getUserDataForSettings();
$actualGender = $dbFunction->getGenderById($userData['genderId']);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <!--    <link rel="stylesheet" href="../css/bootstrap.min.css">-->
    <link rel="stylesheet" href="../css/contentStyles.css">
    <link rel="stylesheet" href="../css/styles.css">

    <script src="http://code.jquery.com/jquery-1.8.3.min.js" type="text/javascript"></script>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <link rel="stylesheet" href="../css/registration.css">
    <script src="http://ajax.aspnetcdn.com/ajax/jquery.validate/1.10.0/jquery.validate.js"
            type="text/javascript"></script>
    <script type="text/javascript" src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
    <script>

        $().ready(function () {
            $("#subForm").validate({
                rules: {
                    settings_firstName: {
                        required: false,
                        minlength: 2,
                        maxlength: 25
                    },
                    settings_surName: {
                        required: false,
                        minlength: 2,
                        maxlength: 25
                    },

                    settings_password: {
                        required: false,
                        minlength: 6,
                        maxlength: 50,
                    },

                    settings_password2: {
                        required: false,
                        minlength: 6,
                        maxlength: 50,
                    },
                    settings_email: {
                        required: false,
                        email: true,
                        minlength: 8,
                        maxlength: 50,
                    },
                    settings_plz: {
                        required: false,
                        minlength: 4,
                        maxlength: 50,
                    },
                    settings_address: {
                        required: false,
                        minlength: 2,
                        maxlength: 50,
                    },
                    settings_gender:{
                        required: true,
                    },
                    settings_birthday:{
                        required: true,
                    },
                    settings_oldPassword:{
                        minlength: 6,
                        required: true,
                    },

                },
                messages: {
                    settings_firstName: {
                        required: "Bitte gebe deinen Vornamen ein!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!")
                    },
                    settings_surName: {
                        required: "Bitte gebe deinen Nachnamen ein!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!")
                    },
                    settings_password:{
                        required: "Bitte gib ein neues gültiges Passwort an!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!")
                    },
                    settings_password2:{
                        required: "Bitte gib ein gültiges Passwort an!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!"),
                        equalTo: "#settings_password"
                    },
                    settings_email: {
                        required: "Bitte gib ein gültiges Passwort an!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!"),
                    },
                    settings_plz: {
                        required: "Bitte gib ein gültiges Passwort an!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!"),
                    },
                    settings_address: {
                        required: "Bitte gib ein gültiges Passwort an!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!"),
                    },
                    settings_oldPassword:{
                        required: "Bitte gib dein aktuelles Passwort ein!",
                        minlength: jQuery.format("Mindestens {0} Zeichen benötigt!"),
                        maxlength: jQuery.format("Maximal {0} Zeichen erlaubt!"),
                    }
                },
                // the errorPlacement has to take the table layout into account
                errorElement: "label",
                errorClass: "error-label",
                validClass: "success",

                errorPlacement: function (error, element) {
                    if($(element).parent().hasClass("oldPassword"))
                    {
                        error.css('width','47vw');
                    }
                    error.insertAfter(element.parent());
                },
                highlight: function (element, errorClass, validClass) {
                    if ($(element).hasClass("success-border")) {
                        $(element).removeClass("success-border");
                    }
                    $(element).addClass("error-border");
                },

                unhighlight: function (element, errorClass, validClass) {
                    $(element).removeClass("error-border");
                },
                success: function (label, element) {
                    $(element).addClass("success-border");
                    $(element).parent().next().remove();

                },

            });



        });
    </script>
</head>

<body class="">
<div id="content">
    <div class="container">
        <div class="row">
            <div class="">
                <form id="subForm" action="../script/userSetting.php" method="POST" class="form login">
                    <div class="links">
                        <div class="input-group">
                            <span class="input-group-addon"><span class=" fa fa-user fa-lg"></span></span>
                            <input id="settings_username" type="text" name="settings_username" class="form-control"
                                   placeholder="Benutzername" readonly>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon "><span class="fa fa-id-card fa-lg"></span></span>
                            <input id="settings_firstName" type="text" name="settings_firstName" class="form-control"
                                   placeholder="Vorname">
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-id-card fa-lg"></span></span>
                            <input id="settings_surName" type="text" name="settings_surName" class="form-control"
                                   placeholder="Nachname">
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span>
                            <input id="settings_password" type="password" name="settings_password" class="form-control"
                                   placeholder="Neues Passwort">
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span>
                            <input id="settings_password2" type="password" name="settings_password2"
                                   class="form-control" placeholder="Neues Passwort wiederholen">
                        </div>


                    </div>

                    <div class="rechts">

                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-at fa-lg"></span></span>
                            <input id="settings_email" type="email" name="settings_email" class="form-control"
                                   placeholder="E-Mail">
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-home fa-lg"></span></span>
                            <input id="settings_plz" type="text" name="settings_plz" class="form-control"
                                   placeholder="Postleitzahl">
                        </div>


                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-road fa-lg"></span></span>
                            <input id="settings_address" type="text" name="settings_address" class="form-control"
                                   placeholder="Straße">
                        </div>
                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-birthday-cake fa-lg"></span></span>
                            <input id="settings_birthday" value="1939-05-30" type="date" name="settings_birthday"
                                   class="form-control" placeholder="Geburtsdatum" readonly>
                        </div>

                        <div class="input-group">
                            <span class="input-group-addon"><span class="fa fa-android fa-lg"></span></span>
                            <select id="settings_gender" class="form-control" name="settings_gender" value="Joghurt"
                                    title="Geschlecht">
                                <option selected>Dobby</option>
                                <?php
                                echo $dbFunction->getGenderData($actualGender);
                                ?>
                            </select>
                        </div>
                    </div>

                    <div class="input-group oldPassword">
                        <span class="input-group-addon"><span class="fa fa-lock fa-lg"></span></span>
                        <input id="settings_oldPassword" type="password" name="settings_oldPassword" class="form-control"
                               placeholder="Altes Passwort bestätigen">
                    </div>
                    <div class="">
                        <input type="submit" value="Ändern!" class="form-control">
                    </div>


                </form>
            </div>
        </div>
    </div>
</div>

</body>
</html>