var siteAddress = 'https://khord.ir/wheaterApp/';

var searchIcon = document.getElementById('searchIcon');
var searchSection = document.getElementById('searchSection');

const errorBlock = document.querySelectorAll('error').length > 0;

window.addEventListener('click', function(e){   
    if (! document.getElementById('searchSection').contains(e.target) && !  document.getElementById('searchIcon').contains(e.target)){
        if (searchSection.classList.contains('fade')) {
            searchSection.classList.remove('fade');
        }
    }
});

searchIcon.addEventListener('click', function (event){
    var searchSection = document.getElementById('searchSection');
    searchSection.classList.toggle('fade');
    if (window.getComputedStyle(searchSection, null).display == 'none') {
        searchSection.style.display == 'flex';
    } else {
        window.getComputedStyle(searchSection, null).display == 'none';
    }
})

if (errorBlock) {
    window.addEventListener('click', function(e){
        if (document.getElementsByClassName('error')[0].contains(e.target)) {
            searchSection.classList.toggle('fade');
        }
    })
    var error = document.getElementsByClassName('error')[0];
    error.addEventListener('click', function (event){
        var searchSection = document.getElementById('searchSection');
        searchSection.classList.toggle('fade');
        if (window.getComputedStyle(searchSection, null).display == 'none') {
            searchSection.style.display == 'flex';
        } else {
            window.getComputedStyle(searchSection, null).display == 'none';
        }
    })
}

var citySearchIcon = document.getElementsByClassName('searchIconDiv')[0];
var addToCities = document.getElementsByClassName('changeButton');
if (addToCities.length > 0) {
    addToCities[0].addEventListener('click', function (event) {
        var searchedCity =  addToCities[0].getAttribute('searchedCity');
        fetch(siteAddress + 'api/addToCities/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({city:searchedCity})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'confirm'
                });
            } else {
                Swal.fire({
                    text: 'added to cities successfully',
                    icon: 'success',
                    confirmButtonText: 'confirm'
                }).then(function (result) {
                    if (result) {
                        window.location.href = siteAddress;
                    }
                })
            }
        })
    })    
}

var chooseCity = document.getElementById('chooseCity');
chooseCity.addEventListener('click', function (event) {
    if (searchSection.classList.contains('fade')) {
        searchSection.classList.remove('fade');
    }
})

var trashCan = document.getElementById('trashCan');
if (trashCan) {
    trashCan.addEventListener('click', function (event) {
        var searchedCity =  addToCities[0].getAttribute('searchedCity');
        fetch(siteAddress + 'api/removeCity/', {
            method:'POST',
            headers: {'Content-Type': 'application/x-www-form-urlencoded'},
            body: new URLSearchParams({city:searchedCity})
        }).then(response => response.json()).then(jsonData => {
            if (jsonData.error) {
                Swal.fire({
                    text: jsonData.message,
                    icon: 'error',
                    confirmButtonText: 'confirm'
                });
            } else {
                Swal.fire({
                    text: 'removed from cities successfully',
                    icon: 'success',
                    confirmButtonText: 'confirm'
                }).then(function (result) {
                    if (result) {
                        window.location.href = siteAddress;
                    }
                })
            }
        })
    })
}