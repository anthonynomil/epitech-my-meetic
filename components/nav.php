<nav class="container f-row f-jc-Center h-75">
    <div class="content f-row f-jc-Between f-ai-Center">
        <?php
        if (isset($_SESSION['user'])) { ?>
            <div>
                <a class="m-20" href="./home.php">My Meetic</a>
                <a class="m-20" href="./search.php">Find your soulmate</a>
            </div>
            <div>
                <a class="m-20" href="#">Messages</a>
                <a class="m-20" href="./profile.php">Profile</a>
                <button class="btn btn-primary my"
                        onclick="logout()">Logout
                </button>
            </div>
            <?php
        } else { ?>
            <div class="m-20">
                <a class="m-20" href="../index.php">My Meetic</a>
            </div>
            <div class="m-20">
                <a class="m-20" href="./login.php">Login</a>
                <a class="m-20" href="./register.php">Register</a>
            </div>
            <?php
        }
        ?>
    </div>
</nav>
