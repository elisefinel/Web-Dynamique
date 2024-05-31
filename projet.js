document.addEventListener("DOMContentLoaded", function() {
    var servicesButton = document.getElementById("services-button");
    servicesButton.addEventListener("click", function() {
        window.location.href = "services.html";
    });
});


document.addEventListener("DOMContentLoaded", function() {
    var servicesList = document.getElementById("services-list");
    var serviceDetails = document.getElementById("service-details");
    var serviceTitle = document.getElementById("service-title");
    var serviceDescription = document.getElementById("service-description");

    var services = {
        covid: {
            title: "Dépistage covid-19",
            description: "Informations sur le dépistage du covid-19, les procédures, et les règles avant, pendant et après le dépistage."
        },
        preventive: {
            title: "Biologie préventive",
            description: "Informations sur la biologie préventive, les types de tests et les conseils pour la prévention."
        },
        pregnancy: {
            title: "Biologie de la femme enceinte",
            description: "Informations sur les tests biologiques pour les femmes enceintes, les règles avant et après les prélèvements."
        },
        routine: {
            title: "Biologie de routine",
            description: "Informations sur les tests de biologie de routine, les procédures et les conseils."
        },
        cancer: {
            title: "Cancérologie",
            description: "Informations sur les prélèvements de sang, d'urine, etc., les règles avant, durant et après ces prélèvements, et d'autres informations pertinentes pour la cancérologie."
        },
        gynecology: {
            title: "Gynécologie",
            description: "Informations sur les tests de gynécologie, les procédures et les conseils."
        }
    };

    servicesList.addEventListener("click", function(event) {
        var serviceKey = event.target.getAttribute("data-service");
        if (serviceKey && services[serviceKey]) {
            serviceTitle.textContent = services[serviceKey].title;
            serviceDescription.textContent = services[serviceKey].description;
            serviceDetails.style.display = "block";
        }
    });
});
