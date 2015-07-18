import os
import glob



def run_scraper():

	with open('dates.txt') as f:

		for line in f:
			
			os.popen('python scraper.py -q finance -d ' + line)
			os.popen('python scraper.py -q conflict -d ' + line)
			os.popen('python scraper.py -q pollution -d ' + line)

def clean_up_library():

	os.chdir("archive/")
	for fil in glob.glob("*.json"):
	    data = open(fil).read()

	    if data == "[":

	    	with open(fil, "w") as half_file:
	    		half_file.write("[]")

def dump_libary():

	os.chdir("archive/")
	for fil in glob.glob("*.json"):
	    data = open(fil).read()
	    print data

run_scraper()
# clean_up_library()

# dump_library()
