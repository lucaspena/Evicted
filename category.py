#!/usr/bin/python
import sys

argList = sys.argv

cat = argList[1]

fixes = {"med" : "Get Well", 
         "hun" : "Eat", 
         "com" : "Relax", 
         "soc" : "Interact", 
         "art" : "Admire", 
         "str" : "Exercise", 
         "slp" : "Rest",
         "oth" : "Random"}

print fixes[cat]

