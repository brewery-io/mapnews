import urllib2
import json
import math
import sys
import getopt
import urllib
import urlparse
import os

def url_fix(s, charset='utf-8'):

	if isinstance(s, unicode):
		s = s.encode(charset, 'ignore')

	scheme, netloc, path, qs, anchor = urlparse.urlsplit(s)
	path = urllib.quote(path, '/%')
	qs = urllib.quote_plus(qs, ':&=')

	return urlparse.urlunsplit((scheme, netloc, path, qs, anchor))

def remove_last(filename):
	with open(filename, 'rb+') as filehandle:
	    filehandle.seek(-1, os.SEEK_END)
	    filehandle.truncate()

def insert(filename, string):

	with open(filename, 'a') as file:
		file.write(string)

def getValues(doc, q):

	finance = ["finance", "economy"]
	conflict = ["conflict", "war"]
	pollution = ["pollution"]

	web_url = doc["web_url"]
	name = doc["snippet"]

	if q in finance:
		category = "finance"
	elif q in conflict:
		category = "conflict"
	elif q in pollution:
		category = "pollution"

	place = False

	if doc["keywords"]:
		for keyword in doc["keywords"]:
			if keyword["name"] == "glocations":
				place = keyword["value"]

	if place is False:
		return False

	else:

		location = getLocation(place)

		if location == False:
			return False

		else:
			return {
					"web_url": web_url,
					"name": name,
					"category": category,
					"place": place,
					"location": location
					}


def getPlacement(location):

	return

def getLocation(place):

	base_uri = "https://maps.googleapis.com/maps/api/geocode/json?address=%s&key=AIzaSyBL9ZBUVjiQs9i2nm9DfiOvOmjzK6G4KzY"
	uri = base_uri % (place)
	

	json_str = urllib2.urlopen(url_fix(uri)).read()
	json_dat = json.loads(json_str)

	if json_dat["status"] == "OK":
		return str(json_dat["results"][0]["geometry"]["location"]["lat"]) + "," + str(json_dat["results"][0]["geometry"]["location"]["lng"])
		
	else:
		return False

def getJSON(q, d, p):

	base_uri = "http://api.nytimes.com/svc/search/v2/articlesearch.json?q=%s&begin_date=%s&end_date=%s&page=%s&api-key=4f2b3784981f13aaa7ce29d39e252976%sA7%sA72129545"
	uri = base_uri % (q, d, d, str(p), "%3", "%3")

	json_str = urllib2.urlopen(uri).read()
	json_dat = json.loads(json_str)

	return json_dat


def main(q, d, p=0):

	finance = ["finance", "economy"]
	conflict = ["conflict", "war"]
	pollution = ["pollution"]

	if q in finance:
		category = "finance"
	elif q in conflict:
		category = "conflict"
	elif q in pollution:
		category = "pollution"

	filename = "archive/" + d + category + ".json"

	json_dat = getJSON(q, d, p)

	insert(filename, "[")

	for doc in json_dat["response"]["docs"]:
		
		values = getValues(doc, q)
		
		if values is not False:
		
			values_str = json.dumps(values)
			insert(filename, values_str + ",")
			
	hits = json_dat["response"]["meta"]["hits"]
	pages = int(math.floor(hits / 10))

	for i in range(1, pages + 1):

	 	json_dat = getJSON(q, d, i)

	 	for doc in json_dat["response"]["docs"]:
			
			values = getValues(doc, q)

			if values is not False:
				
				values_str = json.dumps(values)
				insert(filename, values_str + ",")

	remove_last(filename)
	insert(filename, "]")


def init(argv):

	try:
		opts, args = getopt.getopt(argv, "q:d:p:", ["query=", "date=", "page="])

	except getopt.GetoptError:
		#usage error
		sys.exit(2)

	args = {}

	for opt, arg in opts:

		if opt in ("-q", "--query"):
			args["q"] = arg

		elif opt in ("-d", "--date"):
			args["d"] = arg

		elif opt in ("-p", "--page"):
			args["p"] = arg

	if "q" in args and "d" in args and "p" in args:
		main(args["q"], args["d"], args["p"])

	elif "q" in args and "d" in args:
		main(args["q"], args["d"])

	else:
		sys.exit(2)


if __name__ == "__main__":
	init(sys.argv[1:])