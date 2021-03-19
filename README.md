# Equalstreetnames Munich

Based on Equalstreetnames of OpenKnowledge Belgium

### Laim Workflow

We decided to do one part of the city first: Laim. Restrict to this in the overpass query.
```
area["wikidata"="Q259879"]
```
This is my personal workflow on my Mac. I work my way through the streets, from Z to A.
```
# generate an alphabetical list of streets
cat data/ways.geojson | jq '.features[].properties.name' | sort  -u > streetlist_laim
# get next 10 entries. Say you already did all from Z to K.
grep -B10 "Käthe-Bauer-Weg" data/streetlist_laim
```
We decide to tag "Kärntner Platz". We have the etymology infos in muenchenwiki [https://www.muenchenwiki.de/wiki/K%C3%A4rntner_Platz](https://www.muenchenwiki.de/wiki/K%C3%A4rntner_Platz) . I think that there is a more authoritative source, but this one is quite convenient. We are quite lucky to have this.


Now you will either find a Wikipedia entry (case A), or not (case B). In both cases:
```
# Extract the OSM IDs of the street
STREET="Kärntner Platz"
cat data/ways.geojson | jq --arg street "$STREET" '.features[] | select(.properties.name==$street) | .id' 
# 158803943
```
For this street, we find one id. For Landsberger Straße in Laim, it is 90 IDs. 
In OpenStreetMap, find the Line with this ID.

Case A: This one is in Wikipedia. Go to the article, from there in the left hand side menu to the [Wikidata object](https://www.wikidata.org/wiki/Q37985). Note Q37985. In OpenStreetMap, add a property `name:etymology:wikidata` with this value. Upload. Later this tag will be used to pull the information into the map.

Case B: For "Käpflstraße", there is no Wikipedia article. There is no gender, and two OSM IDs. Add these lines to config.php:
```
            '5080996' => '_', // Käpflstraße: nach der Münchner Kaufmannsfamilie Käpfl.
            '420012960' => '_', // Käpflstraße: nach der Münchner Kaufmannsfamilie Käpfl.
```
