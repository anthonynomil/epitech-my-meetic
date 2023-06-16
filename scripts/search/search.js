window.onload = () => handleSexChange($(`#searchForm select[name='sex']`), $(`#searchForm input[name="otherSex"]`))

function doSearch(event) {
    event.preventDefault();
    const inputs = $("#searchForm .input")
    const inputValues = {
        sex: handleSex(inputs[0].value, inputs[1].value),
        cities: cleanCities(inputs[2].value),
        age: inputs[3].value,
        hobbies: getHobbies("#searchForm", true),
    }
    sendAjax("../scripts/search/checkSearch.php", "post", inputValues, handleSearch);
}

function handleSearch(response) {
    const results = JSON.parse(response)
    $('#searchResult').css('display', 'flex');
    let cards = []
    const error = $("#error")

    if (results.error) {
        error.text(results.error)
        error.show()
        $("#searchResultCarousel").hide()
        $("#buttonCarousel").addClass("hidden")
    } else {
        error.hide()
        for (let result in results) {
            cards.push(createCarouselItem(results[result]))
        }
        $("#searchResultCarousel").css("display", "flex")
        handleCarousel(cards);
    }
}

function cleanCities(cities) {
    let citiesArray = cities.split(",")
    citiesArray = citiesArray.map(city => city.trim())
    return citiesArray.filter(city => city !== "")
}

function handleCarousel(cards) {
    const resultCarousel = $("#searchResultCarousel")
    const buttonCarousel = $("#buttonCarousel")
    resultCarousel.html("")
    buttonCarousel.removeClass("hidden")
    let offset = 1
    let maxOffset = cards.length - 1
    if (cards.length > 3) {
        goNext(offset, cards)
        $("#previous")[0].onclick = () => {
            offset = (offset - 1 < 0) ? maxOffset : offset - 1
            goNext(offset, cards, true)
        }
        $("#next")[0].onclick = () => {
            offset = (offset + 1 > maxOffset) ? 0 : offset + 1
            goNext(offset, cards)
        }
    } else {
        for (const card of cards) {
            resultCarousel.css("justify-content", "center").append(card)
        }
        $("#buttonCarousel").addClass("hidden")
    }
}

function goNext(offset, cards, previous = false) {
    let lastItem = (offset - 1 < 0) ? cards.length - 1 : offset - 1
    let nextItem = (offset + 1 > cards.length - 1) ? 0 : offset + 1
    let innerHTML = cards[lastItem] + cards[offset] + cards[nextItem]
    document.querySelector("#searchResultCarousel").innerHTML = innerHTML
    $("#searchResultCarousel").html(innerHTML)
}

function handleAge(age) {
    const now = new Date()
    const birthdate = new Date(age)
    return now.getFullYear() - birthdate.getFullYear()
}

function createCarouselItem(result) {
    return `<div class="card f-column w-200 p-10 m-20 bg-color-third">
                    <div class="cardHeader">
                        <h3 class="text-white m-10" id="cardName">${result.firstname}</h3>
                    </div>
                    <div class="cardBody">
                        <img src="../assets/placeholder.jpeg" alt="Profile Picture" class="w-200 carouselImg">
                        <div class="f-row f-ai-Center">
                            <p class="text-white m-10">Age: </p>
                            <p class="text-white" id="cardAge">${handleAge(result.birthdate)}</p>
                        </div>
                        <div class="f-row f-ai-Center">
                            <p class="text-white m-10">Sex: </p>
                            <p class="text-white" id="cardSex">${result.sex}</p>
                        </div>
                        <div class="f-row f-ai-Center">
                            <p class="text-white m-10">City: </p>
                            <p class="text-white" id="cardCity">${result.city}</p>
                        </div>
                    </div>
                    <div class="cardFooter f-row f-jc-Center">
                        <button class="btn btn-primary m-20 w-150">Message</button>
                    </div>
                </div>`
}