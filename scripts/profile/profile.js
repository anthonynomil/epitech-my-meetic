window.onload = () => {
    handleSexChange($(`#profileForm select[name='sex']`), $(`#profileForm input[name="otherSex"]`))
    const inputs = $("#profileForm .input")
    let oldValues = {
        firstname: inputs[0].value,
        lastname: inputs[1].value,
        sex: handleSex(inputs[2].value, inputs[3].value),
        birthdate: inputs[4].value,
        email: inputs[5].value,
        city: inputs[6].value,
        password: inputs[7].value,
        passwordConfirm: inputs[8].value,
        hobbies: getHobbies("#profileForm"),
    }

    document.getElementById('profileForm').onsubmit = function (event) {
        event.preventDefault()
        getNewProfileInfos(inputs, oldValues)
    }

}

function getNewProfileInfos(inputs, oldValues) {
    let newValues = {
        firstname: inputs[0].value,
        lastname: inputs[1].value,
        sex: handleSex(inputs[2].value, inputs[3].value),
        birthdate: inputs[4].value,
        email: inputs[5].value,
        city: inputs[6].value,
        password: inputs[7].value,
        passwordConfirm: inputs[8].value,
        hobbies: getHobbies("#profileForm"),
    }

    let dataValues = {}
    Object.keys(oldValues).forEach(key => {
        if (newValues[key].toString() !== oldValues[key].toString()) {
            dataValues[key] = newValues[key]
        }
    })

    if (Object.keys(dataValues).length > 0) {
        let inputChecks = checkInputs(newValues)
        if (inputChecks.inputValid) {
            document.getElementById("errorHobbies").innerText = ''
            document.getElementById("errorHobbies").style.display = "none"
            handleErrors(inputChecks)
            dataValues.updateProfile = true
            sendAjax("../scripts/profile/checkProfile.php", "post", dataValues, handleProfileUpdate)
        } else {
            handleErrors(inputChecks)
        }
    } else {
        document.getElementById("errorHobbies").innerText = "Nothing has changed"
        document.getElementById("errorHobbies").style.display = "block"
    }

}

function handleProfileUpdate(response) {
    if (response === "1") {
        document.getElementById("profileForm").innerHTML = "<h3 class='text-white ta-center'>PROFILE SUCCESSFULLY UPDATED</h3>"
    }
}

function deactivateAccount() {
    if (confirm("Are you sure you want to deactivate your account?")) {
        sendAjax("../scripts/profile/checkProfile.php", "post", {deleteAccount: true}, handleDeactivation)
    }
}

async function handleDeactivation(response) {
    if (response === "true") {
        document.getElementById("profileForm").innerHTML = "<h3 class='m-t-50 text-secondary ta-center'>ACCOUNT SUCCESSFULLY DEACTIVATED</h3>"
        await new Promise(res => setTimeout(res, 2000));
        logout()

    }
}