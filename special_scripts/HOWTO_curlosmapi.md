# How To construct an OSM API call when you have the OSM and Wikidata IDs

Please don't mess around with this. Work with the test API `https://master.apis.dev.openstreetmap.org` before going to prod. 

### Starting Point: Have your IDs

Some friendly soul found the OSM for you, or you found it with and the wikidata id, such as in
```
             '150926694' => 'M', // Lintnerweg
             '907692356' => 'M', // Lintnerweg

wikidata_id: Q1732170
```
Working with curl; Implementing it in php is left as an exercise ;-)

### Create a Changeset
, as in https://wiki.openstreetmap.org/wiki/API_v0.6#Create:_PUT_.2Fapi.2F0.6.2Fchangeset.2Fcreate:
File create_changeset.osm
```
<osm>
<changeset>
<tag k="created_by" v="JOSM 1.61"/>
<tag k="comment" v="adding Lintnerweg wikidata id Q1732170 to name:etymology:wikidata tag"/>
</changeset>
</osm>

curl -XPUT -u<osm_username> -w "%{http_code}" -d @create_changeset.osm https://master.apis.dev.openstreetmap.org/api/0.6/changeset/create

```
It will ask for your password. Response is something like 192917 (and `http_code 200`)

### Get the osm Data for one ID

To get OSM data for an ID, follow https://wiki.openstreetmap.org/wiki/API_v0.6#Read:_GET_.2Fapi.2F0.6.2F.5Bnode.7Cway.7Crelation.5D.2F.23id
```
curl -XGET -u<osm_user_name> -w "%{http_code}" https://master.apis.dev.openstreetmap.org/api/0.6/way/4305697038
Enter host password for user '<osm_user_name>':
<?xml version="1.0" encoding="UTF-8"?>
<osm version="0.6" generator="CGImap 0.8.3 (17751 errol.openstreetmap.org)" copyright="OpenStreetMap and contributors" attribution="http://www.openstreetmap.org/copyright" license="http://opendatacommons.org/licenses/odbl/1-0/">
<way id="4305697038" visible="true" version="1" changeset="192916" timestamp="2021-03-22T14:01:58Z" user="<osm_user_name>" uid="11183">
<nd ref="4327616546"/>
<nd ref="4327616547"/>
<nd ref="4327616548"/>
<nd ref="4327616549"/>
<tag k="highway" v="living_street"/>
<tag k="maxspeed" v="30"/>
<tag k="name" v="Eckermannstrasse"/>
<tag k="oneway" v="no"/>
</way>
</osm>

3. Update street, as in https://wiki.openstreetmap.org/wiki/API_v0.6#Update:_PUT_.2Fapi.2F0.6.2F.5Bnode.7Cway.7Crelation.5D.2F.23id
update_eckermann.osm edited from the above answer, and adding the tag:
-------
<?xml version="1.0" encoding="UTF-8"?>
<osm version="0.6" >
<way id="4305697038" visible="true" version="1" changeset="192917">
<nd ref="4327616546"/>
<nd ref="4327616547"/>
<nd ref="4327616548"/>
<nd ref="4327616549"/>
<tag k="highway" v="living_street"/>
<tag k="maxspeed" v="30"/>
<tag k="name" v="Eckermannstrasse"/>
<tag k="oneway" v="no"/>
<tag k="name:etymology:wikidata" v="Q58018"/>
</way>
</osm>
--------
curl -XPUT -u<osm_user_name> -w "%{http_code}" -d @update_eckermann.osm https://master.apis.dev.openstreetmap.org/api/0.6/way/4305697038

Response:
2200
2 for the version, 200 http_code, and the update is visible in the test environment.

4. Close the changeset:
curl -XPUT -u<osm_user_name> -w "%{http_code}" https://master.apis.dev.openstreetmap.org/api/0.6/changeset/192917/close

(how to know if a changeset is closed: if you do an curl -XGET -u<osm_user_name> -w "%{http_code}" https://master.apis.dev.openstreetmap.org/api/0.6/changeset/192917 and there is a closed_at date)

