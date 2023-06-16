window.onload = () => handleSexChange($(`#registerForm select[name='sex']`), $(`#registerForm input[name="otherSex"]`))

function doRegister(event) {
    event.preventDefault();
    let inputs = $("#registerForm .input")

    inputs = {
        firstname: inputs[0].value,
        lastname: inputs[1].value,
        sex: handleSex(inputs[2].value, inputs[3].value),
        birthdate: inputs[4].value,
        email: inputs[5].value,
        city: inputs[6].value,
        password: inputs[7].value,
        passwordConfirm: inputs[8].value,
        hobbies: getHobbies("#registerForm"),
    }
    let inputChecks = checkInputs(inputs)
    if (inputChecks.inputValid) {
        handleErrors(inputChecks)
        sendAjax("../scripts/register/checkRegister.php", "post", inputs, handleRegister)
    } else {
        handleErrors(inputChecks)
    }
}

function handleRegister(response) {
    if (response === "1") {
        document.getElementById("registerSection").innerHTML = "<h3 class='m-t-50 text-secondary ta-center'>SUCCESSFULLY REGISTERED</h3>"
    }
}