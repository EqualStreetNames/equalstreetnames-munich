[out:json][timeout:300];
( area["admin_level"="10"]["ref:woonplaatscode"="1070"]["wikidata"="Q749"]; )->.a;
(
	way["highway"]["name"]["highway"!="bus_stop"]["highway"!="proposed"](area.a);
	way["place"="square"]["name"](area.a);
);
out body;
>;
out skel qt;