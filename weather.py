#!/usr/bin/python
import sys

argList = sys.argv

city = argList[1]
state = argList[2]

# locations with no straight up changes
fixes = {"Ann_Arbor_Charter_Township" : "Ann_Arbor", 
         "Christiansbrg"              : "Christiansburg", 
         "Hampshire"                  : "Northampton", 
         "Harlem"                     : "Manhattan", 
         "Lansingburgh"               : "Albany", 
         "Mid-Cambridge"              : "Cambridge", 
         "Philly"                     : "Philadelphia",
         "Princeton_Jct"              : "Princeton_Junction",
         "Riverdale_Park"             : "Riverdale", 
         "Southern_California"        : "Los_Angeles",
         "St._Jacobs"                 : "Kitchener", 
         "St_Jacobs"                  : "Kitchener", 
         "Victoria_Park"              : "Kitchener", 
         "Waterloo"                   : "Kitchener",
         "Westwood_Village"           : "Westwood"}


if city in fixes:
  city = fixes[city]

# path for weather uses underscores
city = city.replace(" ", "_")

print state + "/" + city

