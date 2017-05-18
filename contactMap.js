'use strict';

/* contact MAP
/* ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## ## */

var circle = L.icon({
    iconUrl: './img/marker.svg',
    iconSize: [38, 95],
    popupAnchor:  [0, -20]
});

var map = L.map('map',{scrollWheelZoom:false}).setView([47.06353, 28.8676], 15);

L.tileLayer('http://{s}.tile.osm.org/{z}/{x}/{y}.png').addTo(map);

L.marker([47.06353, 28.8676], {icon: circle}).addTo(map).bindPopup('Positron Bohemia<br>str. Studenților 9/11').openPopup();
