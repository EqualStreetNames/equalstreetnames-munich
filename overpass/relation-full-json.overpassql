[out:json][timeout:300];
( area["admin_level"="6"]["wikidata"="Q1726"]; )->.a;
(
    relation["type"="multipolygon"]["highway"]["name"](area.a);
);
out body;
>;
out skel qt;
