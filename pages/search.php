<?php session_start();
include_once "../scripts/global/globalFunctions.php";
?>
<head>
    <?php include_once "../components/layout/header.php"; ?>
    <script src="../scripts/global/globalFunctions.js"></script>
    <script src="../scripts/search/search.js"></script>
    <title>Find your soulmate</title>
</head>
<body class="container f-column f-jc-Center f-ai-Center">
<div class="bg"></div>
<div class="bg bg2"></div>
<div class="bg bg3"></div>
<?php include_once "../components/nav.php"; ?>
<section class="container w-full f-column f-jc-Center f-ai-Center" id="searchSection">
    <form class="w-500 f-column m-t-50 bg-color-third b-r-10" onsubmit="doSearch(event)" id="searchForm">
        <h1 class="m-20 m-b-25 text-white">Find your soulmate: </h1>
        <select class="input input-group-select m-20" name="sex">
            <option disabled selected value="">Sex</option>
            <option value="Male">Male</option>
            <option value="Female">Female</option>
            <option value="Other">Other</option>
        </select>
        <input type="text" class="input input-group-text m-20 hidden" placeholder="Custom Sex" name="otherSex">
        <p class="error m-20" id="errorSex"></p>
        <input class="input input-group-text m-20" type="text" placeholder="City separate by comma for multiple search">
        <select class="input input-group-select m-20">
            <option selected disabled value="">Age</option>
            <option value="18-25">18/25</option>
            <option value="25-35">25/35</option>
            <option value="35-45">35/45</option>
            <option value="45">45+</option>
        </select>
        <div class="f-row f-wrap f-jc-Even h-200 overflow-y m-20">
            <?php
            $hobbies = getHobbies(true);
            foreach ($hobbies as $key => $value) {
                $value = ucfirst($value);
                echo "<label class='input-group-checkbox m-20 w-100 text-white'>$value<input class='input' type='checkbox' value='$value'><span class='checkmark'></span></label>";
            }
            ?>
        </div>
        <button type="submit" class="btn btn-primary m-20 w-150">Search</button>
    </form>
    <div class="w-750 f-column" id="searchResult" style="display: none">
        <div class="w-750 f-column m-t-50">
            <h1 class="m-20 m-b-25 text-primary">Search Result: </h1>
            <p class="error m-20 m-b-200" id="error"></p>
            <div class="f-row f-jc-Between" id="searchResultCarousel">
            </div>
            <div class="f-row f-jc-Center" id="buttonCarousel">
                <button class="btn btn-primary m-20 w-150" id="previous">Previous</button>
                <button class="btn btn-primary m-20 w-150" id="next">Next</button>
            </div>
        </div>
</section>
</body>
