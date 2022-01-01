import configparser, requests, json, logging
import xml.etree.ElementTree as ET

logging.basicConfig(level=logging.DEBUG)

config = configparser.ConfigParser()
config.read('config.ini')

osm_ids = json.loads(config['IDS']['osm_ids'])
wikidata_id = config['IDS']['wikidata_id']
osm_user = config['SECRETS']['osm_user']
osm_password = config['SECRETS']['osm_password']

# open changeset for this wikidata_id
changeset_data = """
<osm>
<changeset>
<tag k="created_by" v="JOSM 1.61"/>
<tag k="comment" v="Adding wikidata id """ + wikidata_id + """ "/>
</changeset>
</osm>
"""
url_changeset = "https://api.openstreetmap.org/api/0.6/changeset"
response = requests.request("PUT", url_changeset + "/create", data = changeset_data , auth=(osm_user, osm_password))
changeset_id = response.text
logging.debug("changeset: " + str(changeset_id))

name_etymology_wikidata = ""
for id in osm_ids:
  url_way = "https://api.openstreetmap.org/api/0.6/way/" + id
  tree = ET.ElementTree(ET.fromstring(requests.request("GET", url_way).text))
  root = tree.getroot()
  for tag in root.iter('tag'):
    if tag.get('k') == 'name:etymology:wikidata':
      name_etymology_wikidata = tag.get('v')

  if name_etymology_wikidata:
    logging.info("We already have name:etymology:wikidata " + name_etymology_wikidata)
  else:
    way = tree.find('way')
    way.attrib["changeset"] = str(changeset_id)
    ET.SubElement(way, "tag", attrib={"k": "name:etymology:wikidata", "v": wikidata_id})
    ET.dump(tree)
    response = requests.request("PUT", url_way, data = ET.tostring(root), auth=(osm_user, osm_password))
    logging.debug(response._content)

response = requests.request("PUT", url_changeset + "/" + str(changeset_id) + "/close", auth=(osm_user, osm_password))
logging.debug(response.text)