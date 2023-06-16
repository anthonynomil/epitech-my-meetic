<?php session_start(); ?>
<head>
    <?php include_once "../components/layout/header.php"; ?>
    <script src="../scripts/global/globalFunctions.js"></script>
    <script src="../scripts/login/login.js"></script>
    <title>Login</title>
</head>
<body class="container f-column f-jc-Center f-ai-Center">
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<?php include_once "../components/nav.php"; ?>
<section class="container f-row f-jc-Center" id="loginSection">
    <form class="content w-500 f-column m-t-tenth bg-color-third b-r-10" id="loginForm" onsubmit="doLogin(event)">
        <h1 class="text-white m-20">Login: </h1>
        <input class="input input-group-text m-20" type="text" placeholder="Email">
        <input class="input input-group-text m-20" type="password" placeholder="Password">
        <p class="error m-20" id="errorLogin"></p>
        <button class="btn btn-primary w-150 m-20" type="submit">Login</button>
    </form>
</section>
</body>
