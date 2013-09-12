#!/usr/bin/python
import sys

argList = sys.argv

text = argList[1]

# food
goodf = ["awesome", "good", "great", "smile", "free", "happy", "pleasantly", "pleasant", "helpful", "sweet", "yummy", "amazing", "fresh", "friendly", "excellent", "best", "better", "fantastic", "wonderful", "extraordinary", "fast", "fastest"]
badf = ["upset", "bad", "horrible", "indigestion", "sick", "dirty", "gross", "nasty", "unpleasant", "greasy", "oily", "closed", "worst", "hate", "hated"]

# price
cheap = ["cheap", "inexpensive", "affordable", "thrifty", "economical", "bargain", "budget"]
exp = ["expensive", "pricey", "costly", "fancy", "extravagent", "overpriced"]

# satisfaction
goods = ["awesome", "good", "great", "smile", "free", "happy", "pleasantly", "pleasant", "helpful", "sweet", "rare", "unique", "amazing", "fresh", "friendly", "excellent", "best", "better", "fantastic", "wonderful", "extraordinary", "clean", "nice", "respectful", "festive", "entertaining", "entertainment", "fun", "funny", "cool"]
bads = ["upset", "bad", "horrible", "dirty", "unpleasant", "closed", "boring", "lame", "stupid", "dumb", "annoying", "frustrating", "noisy", "unhelpful", "uncomfortable", "rude", "worse", "racist", "worst", "hate", "hated"]

# health
healthy = ["fresh", "vegetable", "vegetables", "healthy", "fruit", "fruits", "fruity", "protein", "health"]
unhealthy = ["oily", "greasy", "fatty", "fat", "grease", "oil", "oils", "fats", "carbs", "lard", "rats", "bugs", "rat", "poison", "bug", "poisoning", "sickness", "illness", "sick", "ill", "indigestion", "ache", "heartburn", "death", "dead", "attack", "stroke", "artery", "arteries"]

food = 0
price = 0
price1 = 0
price2 = 0
satis = 0
health = 0
health1 = 0
health2 = 0
text = text.split()

for word in text:
  if word.lower() in goodf:
    food = food + 1
  elif word.lower() in badf:
    food = food - 1
  if word.lower() in cheap:
    price1 = price1 + 1
  elif word.lower() in exp:
    price2 = price2 + 1 
  if word.lower() in goods:
    satis = satis + 1
  elif word.lower() in bads:
    satis = satis - 1
  if word.lower() in healthy:
    health1 = health1 + 1
  elif word.lower() in unhealthy:
    health2 = health2 + 1

if price1 > price2:
  price = 1
elif price1 < price2:
  price = 3
else:
  price = 2

health = health1 - health2

# json
print "{ \"food\" :", food, ", \"price\" :", price, ", \"satis\" :", satis, ", \"health\" :", health, "}"
