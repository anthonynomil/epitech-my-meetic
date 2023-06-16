function logout() {
    sendAjax('../scripts/global/clearSession.php', 'post', {sessionDestroy: true}, redirectIndex)
}

function sendAjax(destination, method, data, doThat) {
    $.ajax({
        url: destination,
        type: method,
        data: data,
        success: (response) => {
            if (doThat !== null) {
                doThat(response)
            }
        },
    })
}

function redirectIndex() {
    const redirect = document.createElement('a')
    redirect.setAttribute('href', '../index.php')
    redirect.style.display = 'none'
    document.querySelector('body').appendChild(redirect)
    redirect.click()
}

function checkInputs(inputs) {
    let inputError = {
        inputValid: true,
        errors: {
            0: {
                msg: verifyName(inputs.firstname),
                id: "errorFirstName",
            },
            1: {
                msg: verifyName(inputs.lastname),
                id: "errorLastName"
            },
            2: {
                msg: verifySex(inputs.sex),
                id: "errorSex"
            },
            3: {
                msg: verifyDate(inputs.birthdate),
                id: "errorDate",
            },
            4: {
                msg: verifyEmail(inputs.email),
                id: "errorEmail",
            },
            5: {
                msg: verifyPassword(inputs.password, inputs.passwordConfirm),
                id: "errorPassword",
            },
            6: {
                msg: verifyHobbies(inputs.hobbies),
                id: "errorHobbies",
            },
            7: {
                msg: verifyCity(inputs.city),
                id: "errorCity",
            }
        }
    }
    for (let i = 0; i < 8; ++i) {
        if (inputError.errors[i].msg !== "inputValid") {
            inputError.inputValid = false
        }
    }
    return inputError
}

function verifyName(name) {
    const hasNumbSpeChar = /[`!@#$%^&*()_+=\[\]{};':"\\|,.<>\/?~0-9]/
    if (name === "") return "Please enter a name"
    if (name.length < 2) return "Please enter a longer name"
    if (hasNumbSpeChar.test(name)) return "Please enter a valid name"
    else return "inputValid"
}

function verifySex(sex) {
    if (sex === "") return "Please choose your sex"
    else return "inputValid"
}

function verifyEmail(email) {
    const checkEmail = /(?!(^[.-].*|[^@]*[.-]@|.*\.{2,}.*)|^.{254}.)([a-zA-Z0-9!#$%&'*+\/=?^_`{|}~.-]+@)(?!-.*|.*-\.)([a-zA-Z0-9-]{1,63}\.)+[a-zA-Z]{2,15}/
    if (email === "") return "Please enter an email"
    if (email.length < 2) return "Please enter a valid email"
    if (checkEmail.test(email)) return "inputValid"
    else return "Please enter a valid email"
}

function verifyPassword(password, passwordConfirm) {
    if (password === "") return "Please enter a password"
    if (password < 8) return "Please enter a password of 8 or more characters"
    if (password !== passwordConfirm) return "Passwords do not match"
    else return "inputValid"
}

function verifyDate(date) {
    date = new Date(date)
    const today = new Date()
    if (date === "") return "Please enter a date"
    if (date >= today) return "Please enter a valid date"
    if (today.getFullYear() - date.getFullYear() < 18) return "You must be 18 or older to register"
    else return "inputValid"
}

function verifyHobbies(hobbies) {
    if (hobbies.length < 1) return "Please choose at least one hobby"
    else return "inputValid"
}

function verifyCity(city) {
    if (city === "") return "Please enter a city"
    else return "inputValid"
}

function handleErrors(object) {
    Object.keys(object.errors).forEach(key => errorMsg(object.errors[key].msg, object.errors[key].id))
}

function errorMsg(msg, id) {
    const elem = $("#" + id)
    if (msg !== "inputValid") {
        elem.text(msg)
        elem.show()
    } else {
        elem.hide()
    }
}

function todayFormatted() {
    const today = new Date()
    const year = today.getFullYear()
    let month = today.getMonth()
    let day = today.getDay()

    if (day < 10) day = "0" + day
    if (month < 10) month = "0" + month

    return year + "-" + month + "-" + day
}

function getHobbies(id, formatted = false) {
    const checkbox = $(`${id} input[type='checkbox']`)
    let hobbies = [];
    for (const element of checkbox) {
        if (element.checked) {
            hobbies.push(element.value.toLowerCase());
        }
    }
    if (!formatted) {
        let otherHobbies = $(`${id} input[name='otherHobbies']`)[0].value
        otherHobbies.split(',').forEach(elem => (elem.trim() !== "") ? hobbies.push(elem.trim().toLowerCase()) : false)
    }
    return hobbies
}


function handleSex(elemSelect, elemText) {
    if (elemSelect === "Other") {
        return elemText
    } else {
        return elemSelect
    }
}

function handleSexChange(elemSelect, elemText) {
    if (elemSelect === "Other") {
        elemText[0].style.display = "flex"
    }
    elemSelect[0].onchange = () => {
        elemText[0].style.display = (elemSelect[0].value === "Other") ? "flex" : "none"
    }
}
