<?php session_start();
include_once "../scripts/global/globalFunctions.php";
include_once "../scripts/global/checkErrors.php";
checkErrors();
?>
<head>
    <?php include_once "../components/layout/header.php"; ?>
    <title>Registration</title>
    <script src="../scripts/global/globalFunctions.js"></script>
    <script src="../scripts/register/register.js"></script>
</head>
<body class="container f-column f-jc-Center f-ai-Center">
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<?php include_once "../components/nav.php"; ?>
<section class="container f-row f-jc-Center" id="registerSection">
    <form class="content w-500 f-column m-t-50 bg-color-third b-r-10" id="registerForm" onsubmit="doRegister(event)">
        <h1 class="text-white m-20">Register: </h1>
        <input class="input input-group-text m-20" type="text" placeholder="First Name">
        <p class="error m-20" id="errorFirstName"></p>
        <input class="input input-group-text m-20" type="text" placeholder="Last Name">
        <p class="error m-20" id="errorLastName"></p>
        <select class="input input-group-select m-20" name="sex">
            <option selected disabled value="">Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="input input-group-text m-20 hidden" placeholder="Custom Sex" name="otherSex">
        <p class="error m-20" id="errorSex"></p>
        <input type="date" class="input m-20 input-group-date">
        <p class="error m-20" id="errorDate"></p>
        <input class="input input-group-text m-20" type="email" placeholder="Email">
        <p class="error m-20" id="errorEmail"></p>
        <input class="input input-group-text m-20" type="text" placeholder="City">
        <p class="error m-20" id="errorCity"></p>
        <input class="input input-group-text m-20" type="password" placeholder="Password">
        <input class="input input-group-text m-20" type="password" placeholder="Confirm Password">
        <p class="error m-20" id="errorPassword"></p>
        <h2 class="m-20 text-white">Hobbies: </h2>
        <div class="f-row f-wrap f-jc-Even h-200 overflow-y m-20">
            <?php
            $hobbies = getHobbies(true);
            foreach ($hobbies as $key => $value) {
                $value = ucfirst($value);
                echo "<label class='input-group-checkbox m-20 w-100 text-white'>$value<input class='input' type='checkbox' value='$value'><span class='checkmark'></span></label>";
            }
            ?>
            <label class="m-20 m-t-25 text-white">Other Hobbies separated by a comma:<input type="text"
                                                                                            name="otherHobbies"
                                                                                            class="input input-group-text m-t-25"
                                                                                            placeholder="Other Hobbies"></label>
        </div>
        <p class="error m-20" id="errorHobbies"></p>
        <button class="btn btn-primary w-150 m-20" type="submit">Register</button>
    </form>
</section>
</body>
