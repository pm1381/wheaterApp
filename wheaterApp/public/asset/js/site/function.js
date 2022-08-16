var siteAddress = 'https://khord.ir/wheaterApp/';

function cityChange(selectedCity) {
    fetch(siteAddress + 'api/changeCity/', {
        method:'POST',
        headers: {'Content-Type': 'application/x-www-form-urlencoded'},
        body: new URLSearchParams({city:selectedCity})
    }).then(response => response.json()).then(jsonData => {
        window.location.href = siteAddress;
    })
}