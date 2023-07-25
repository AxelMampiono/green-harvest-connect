// Fonction pour remplir les champs d'adresse
function fillAddressFields(addressData) {
    document.getElementById('street_number').value = addressData.street_number || '';
    document.getElementById('street_name').value = addressData.street_name || '';
    document.getElementById('postal_code').value = addressData.postal_code || '';
    document.getElementById('city').value = addressData.city || '';
    document.getElementById('country').value = addressData.country || '';
  }
  
  // Fonction pour obtenir la géolocalisation et remplir les champs d'adresse
  function getLocationAndFillAddressFields() {
    if ("geolocation" in navigator) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var latitude = position.coords.latitude;
        var longitude = position.coords.longitude;
  
        var apiKey = "eec999ce1acd37703f8492ad4fb8436c";
        var apiUrl = "http://api.positionstack.com/v1/reverse?access_key=" + apiKey + "&query=" + latitude + "," + longitude;
  
        fetch(apiUrl)
          .then(function(response) {
            return response.json();
          })
          .then(function(data) {
            if (data.data && data.data.length > 0) {
              var addressData = {
                street_number: data.data[0].street_number,
                street_name: data.data[0].street,
                postal_code: data.data[0].postal_code,
                city: data.data[0].city,
                country: data.data[0].country
              };
              fillAddressFields(addressData);
            } else {
              console.error("Impossible de trouver l'adresse.");
            }
          })
          .catch(function(error) {
            console.error("Erreur lors de la récupération de l'adresse : " + error);
          });
      }, function(error) {
        console.error("Erreur de géolocalisation : " + error.message);
      });
    } else {
      console.error("La géolocalisation n'est pas prise en charge par votre navigateur.");
    }
  }
  
  // Appeler getLocationAndFillAddressFields() lorsque le contenu de la page est complètement chargé
  document.addEventListener('DOMContentLoaded', function() {
    getLocationAndFillAddressFields();
  });
  
  const menuHamburger = document.getElementById('menu_hamburger');
  const navbarLinks = document.querySelector('.navbar-links');

  menuHamburger.addEventListener('click', () => {
      navbarLinks.classList.toggle('show');
  });