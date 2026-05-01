// agent-locator.js
// 
// //map custom style
const mapStyle = [
  {
    featureType: 'water',
    elementType: 'geometry',
    stylers: [
      { color: '#ffffff' }, // Change the water color
    ],
  },
  {
    featureType: 'landscape',
    elementType: 'geometry',
    stylers: [
      { color: '#808080' }, // Change the landscape color
    ],
  },
  {
    featureType: 'poi',
    elementType: 'geometry',
    stylers: [
      { color: '#ff0000' }, // Change the Points of Interest color
    ],
  },
];

  
  
  let map;
  let infowindow;
  let agentsGeoJson;
  let currentSelectedFeature;
  let currentInfowindow = null;
  
  async function fetchAgentsData() {
    try {
      const response = await fetch('/agent-locator/json/agents.json');
      if (!response.ok) {
        throw new Error('Network response was not ok');
      }
  
      agentsGeoJson = await response.json();
      console.log('Data fetched:', agentsGeoJson);
  
      return agentsGeoJson;
    } catch (error) {
      console.error('Error:', error);
    }
  }
  
// Initialize the map
function initMap() {
    fetchAgentsData()
      .then(data => {
        agentsGeoJson = data;
        const canada = { lat: 56.1304, lng: -106.3468 };
        map = new google.maps.Map(document.getElementById('map'), {
          zoom: 4,
          center: canada,
          styles: mapStyle, // Add this line to apply the custom map style
        });
  
        // Load GeoJSON.
        map.data.addGeoJson(agentsGeoJson);
  
        // When the user clicks, set 'isColorful', changing the color of the letters.
        map.data.addListener('click', event => {
          console.log('Map data clicked', event);
          if (currentSelectedFeature) {
            map.data.revertStyle();
          }
  
          currentSelectedFeature = event.feature;
          map.data.overrideStyle(event.feature, { strokeWeight: 8 });
  
          const agentInfo = event.feature.getProperty('agentName');
          console.log('Agent info:', agentInfo);
  
          const agentSalesRegion = event.feature.getProperty('salesRegion');
          const agentPhone = event.feature.getProperty('phone');
          const agentEmail = event.feature.getProperty('email');
          const agentImageUrl = event.feature.getProperty('imageUrl');
  
          const contentString =
            '<div id="content">' +
            '<div id="siteNotice">' +
            '</div>' +
            `<img src="${agentImageUrl}" alt="${agentInfo}" width="260px">` +
            `<h1 id="firstHeading" class="firstHeading">${agentInfo}</h1>` +
            '<div id="bodyContent">' +
            `<p><b>Sales Region:</b> ${agentSalesRegion}</p>` +
            `<p><b>Phone:</b><a href="tel: ${agentPhone}"> ${agentPhone} </a></p>` +
            `<p><b>Email:</b><a href="mailto: ${agentEmail}"> ${agentEmail}</a></p>` +
            '</div>' +
            '</div>';
  
          if (currentInfowindow) {
            currentInfowindow.close();
          }
  
			infowindow = new google.maps.InfoWindow({
				content: contentString,
				closeButton: false,
			});

  
          infowindow.setPosition(event.latLng);
          infowindow.open(map);
          currentInfowindow = infowindow;
        });
  
        map.addListener('click', event => {
          if (infowindow) {
            infowindow.close();
          }
  
          if (currentSelectedFeature) {
            map.data.revertStyle();
            currentSelectedFeature = null;
          }
        });
  
        // Create the search box and link it to the UI element.
        const input = document.getElementById('pac-input');
        const searchBox = new google.maps.places.SearchBox(input);
  
        // Bias the SearchBox results towards current map's viewport.
        map.addListener('bounds_changed', () => {
          searchBox.setBounds(map.getBounds());
        });
  
        searchBox.addListener('places_changed', () => {
            const places = searchBox.getPlaces();
            console.log('Places:', places);
          
            if (places.length === 0) {
              return;
            }
          
            // For each place, get the icon, name, and location.
            const bounds = new google.maps.LatLngBounds();
            let hasAgents = false;
          
            places.forEach(place => {
              if (!place.geometry) {
                console.log('Returned place contains no geometry');
                return;
              }
          
              console.log('Place geometry:', place.geometry);
          
              agentsGeoJson.features.forEach(feature => {
                const polygon = feature.geometry.coordinates[0].map(([lng, lat]) => ({
                  lat,
                  lng,
                }));
                console.log('Checking point in polygon:', place.geometry.location, polygon);
                if (
                  google.maps.geometry.poly.containsLocation(
                    place.geometry.location,
                    new google.maps.Polygon({ paths: polygon })
                  )
                ) {
                  console.log('Point is inside the polygon');
                  hasAgents = true;
                  if (infowindow) {
                    infowindow.close();
                  }
          
                  const agentInfo = feature.properties;
                  const agentName = agentInfo.agentName;
                  const agentSalesRegion = agentInfo.salesRegion;
                  const agentPhone = agentInfo.phone;
                  const agentEmail = agentInfo.email;
                  const agentImageUrl = agentInfo.imageUrl;
          
                  const contentString =
                    '<div id="content">' +
                    '<div id="siteNotice">' +
                    '</div>' +
					`<img src="${agentImageUrl}" alt="${agentName}" width="260px">` +
                    `<h1 id="firstHeading" class="firstHeading">${agentName}</h1>` +
                    '<div id="bodyContent">' +
                    `<p><b>Sales Region:</b> ${agentSalesRegion}</p>` +
     		       	`<p><b>Phone:</b><a href="tel: ${agentPhone}"> ${agentPhone} </a></p>` +
      		     	`<p><b>Email:</b><a href="mailto: ${agentEmail}"> ${agentEmail}</a></p>` +
                    '</div>' +
                    '</div>';
          
                  if (currentInfowindow) {
                    currentInfowindow.close();
                  }
          
					infowindow = new google.maps.InfoWindow({
						content: contentString,
						closeButton: false,
					});

          
                  infowindow.setPosition(place.geometry.location);
                  infowindow.open(map);
                  currentInfowindow = infowindow;
          
                  bounds.union(place.geometry.viewport);
                }
              });
            });
  
          if (!hasAgents) {
            const messageContent =
              '<div id="content">' +
              '<div id="siteNotice">' +
              '</div>' +
              '<h1 id="firstHeading" class="firstHeading">No Agents Available</h1>' +
              '<div id="bodyContent">' +
              '<p>There are no agents available for this location.</p>' +
              '</div>' +
              '</div>';
  
            if (infowindow) {
              infowindow.close();
            }
  
            infowindow = new google.maps.InfoWindow({
              content: messageContent,
				closeButton: false,
            });
  
            infowindow.setPosition(map.getCenter());
            infowindow.open(map);
            currentInfowindow = infowindow;
          }
  
          if (hasAgents) {
            map.fitBounds(bounds);
            if (map.getZoom() > 8) {
              map.setZoom(8); // Limit the maximum zoom level to 8
            }
          }
        });
      })
      .catch(error => {
        console.error('Error:', error);
      });
  }
  
  // Call the initMap function when the page has finished loading
  window.onload = function () {
  	initMap();
  };