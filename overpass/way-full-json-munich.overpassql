[out:json][timeout:300];
( area["wikidata"="Q1726"]; )->.a;
(
	way["highway"]["name"]["highway"!="bus_stop"]["highway"!="proposed"]["highway"!="service"]["highway"!="platform"](area.a);
	way["place"="square"]["name"](area.a);
);
out body;
>;
out skel qt;

