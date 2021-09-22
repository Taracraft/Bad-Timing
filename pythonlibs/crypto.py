# -*- coding: utf-8 -*-

import urllib
import json

API_KEY = "58f662b3d9028fe8ca2e52a3682d8390c5b02915f991d0d14f73fcec36e34228"
API_CUR = "USD"
API_URL = "https://min-api.cryptocompare.com/data/pricemulti"
API_CCUR= "BTC,LTC,ETH,DOGE,IOTA"

API_FULL_URL = API_URL + "?fsyms=" + API_CCUR + "&tsyms=" + API_CUR

class getLiveData(object):
	def fetch(self):
		try:
			self.__get_all__()
		except IOError, err:
			return False
		return True
	
	def __get_all__(self):
		self.__json__ = json.loads(urllib.urlopen(API_FULL_URL).read().decode())
	
	def getBTC(self):
		return self.__json__["BTC"]["USD"]
	
	def getLTC(self):
		return self.__json__["LTC"]["USD"]
	
	def getETH(self):
		return self.__json__["ETH"]["USD"]
	
	def getDOGE(self):
		return self.__json__["DOGE"]["USD"]
	
	def getIOTA(self):
		return self.__json__["IOTA"]["USD"]

if __name__ == '__main__':
	data = getLiveData()
        if not data.fetch():
            print "Fehler beim Holen der Kurse!"
        else:
            btc = data.getBTC()
            ltc = data.getLTC()
	    eth = data.getETH()
	    doge = data.getDOGE()
	    iota = data.getIOTA()
	    print("1 BTC = {}\n1 LTC = {}\n1 ETH = {}\n1 DOGE = {}\n1 IOTA = {}".format(btc,ltc,eth,doge,iota))
