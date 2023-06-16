function doLogin(event) {
    event.preventDefault()
    const inputs = $("#loginForm .input")
    const loginData = {
        email: inputs[0].value,
        password: inputs[1].value,
    }
    sendAjax("../scripts/login/checkLogin.php", "post", loginData, handleLogin)
}

function handleLogin(result) {
    if (result === "1") {
        redirectIndex()
    } else {
        $("#errorLogin").text('Wrong credentials').show();
    }
}
