<?php
session_start();
include_once '../classes/USER.php';
include_once '../scripts/global/checkErrors.php';
include_once '../scripts/global/globalFunctions.php';
checkErrors();
$firstname = $_SESSION['user']['firstname'];
$lastname = $_SESSION['user']['lastname'];
$sex = $_SESSION['user']['sex'];
$city = $_SESSION['user']['city'];
$email = $_SESSION['user']['email'];
$birthdate = $_SESSION['user']['birthdate'];
$hobbies = $_SESSION['user']['hobbies'];
?>
<head>
    <?php include_once "../components/layout/header.php"; ?>
    <title>Profile</title>
    <script src="../scripts/global/globalFunctions.js"></script>
    <script src="../scripts/profile/profile.js"></script>
</head>
<body class="container f-column f-jc-Center f-ai-Center">
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<?php include_once "../components/nav.php"; ?>
<section class="container f-row f-jc-Center" id="registerSection">
    <form class="content w-500 f-column m-t-50 bg-color-third b-r-10 f-jc-Center" id="profileForm"
          onsubmit="return false">
        <h1 class="text-white m-20">Your information: </h1>
        <?php
        echo "<input class='input input-group-text m-20' type='text' placeholder='First Name' value='$firstname'>"
        ?>
        <p class="error m-20" id="errorFirstName"></p>
        <?php
        echo "<input class='input input-group-text m-20' type='text' placeholder='Last Name' value='$lastname'>";
        ?>
        <p class="error m-20" id="errorLastName"></p>
        <?php
        echo "<select class='input input-group-select m-20' name='sex'>";
        echo match ($sex) {
            'Male' => "<option disabled value=''>Sex</option>
                      <option value='Male' selected>Male</option>
                      <option value='Female'>Female</option>
                      <option value='Other'>Other</option>",
            'Female' => "<option disabled value=''>Sex</option>
                      <option value='Male'>Male</option>
                      <option value='Female' selected>Female</option>
                      <option value='Other'>Other</option>",
            default => "<option disabled value=''>Sex</option>
                      <option value='Male'>Male</option>
                      <option value='Female'>Female</option>
                      <option value='Other' selected>Other</option>",
        };
        echo "</select>";
        echo match ($sex) {
            'Male', 'Female' => "<input class='input input-group-text m-20' type='text' name='otherSex' placeholder='Custom Sex'>",
            default => "<input class='input input-group-text m-20' type='text' name='otherSex' placeholder='Custom Sex' value='$sex'>",
        };
        ?>
        <p class="error m-20" id="errorSex"></p>
        <?php
        echo "<input type='date' class='input m-20 input-group-date' value='$birthdate'>";
        ?>
        <p class="error m-20" id="errorDate"></p>
        <?php
        echo "<input class='input input-group-text m-20' type='email' placeholder='Email' value='$email'>";
        ?>
        <p class="error m-20" id="errorEmail"></p>
        <?php
        echo "<input class='input input-group-text m-20' type='text' placeholder='City' value='$city'>"
        ?>
        <p class="error m-20" id="errorCity"></p>
        <input class="input input-group-text m-20" type="password" placeholder="Password" value="PassW0Rd">
        <input class="input input-group-text m-20" type="password" placeholder="Confirm Password" value="PassW0Rd">
        <p class="error m-20" id="errorPassword"></p>
        <h2 class="m-20 text-white">Hobbies: </h2>
        <div class="f-row f-wrap f-jc-Even h-200 overflow-y m-20">
            <?php
            $dbHobbies = getHobbies(true);
            foreach ($dbHobbies as $key => $value) {
                if (in_array($value, $hobbies, true)) {
                    $value = ucfirst($value);
                    echo "<label class='input-group-checkbox m-20 w-100 text-white'>$value<input class='input' type='checkbox' value='$value' checked><span class='checkmark'></span></label>";
                } else {
                    $value = ucfirst($value);
                    echo "<label class='input-group-checkbox m-20 w-100 text-white'>$value<input class='input' type='checkbox' value='$value'><span class='checkmark'></span></label>";
                }
            }
            ?>
            <label class="m-20 m-t-25 text-white">Other Hobbies separated by a comma:
                <input name='otherHobbies' type="text" class="input input-group-text m-t-25"
                       placeholder="Other Hobbies">
            </label>
        </div>
        <p class="error m-20" id="errorHobbies"></p>
        <div class="f-row w-500 f-jc-Between">
            <button class="btn btn-primary w-150 m-20" type="submit">Update</button>
            <button class="btn btn-primary w-250 m-20" type="button" onclick="deactivateAccount()">Delete account
            </button>
        </div>
    </form>
</section>
</body>
