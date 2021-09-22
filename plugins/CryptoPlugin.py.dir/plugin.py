import sys
sys.path.append("./pythonlibs/")

import crypto

class CryptoPlugin(PythonPlugin):
	def onEnable(self):
		self.getLogger().info("CryptoPlugin enabled!")
	def onDisable(self):
		self.getLogger().info("CryptoPlugin disabled!")
	
	def onCommand(self, sender, command, label, args):
		command = command.getName().decode().lower()
		if len(args) < 1:
			return False
		cdata = crypto.getLiveData()
                if not cdata.fetch():
                    sender.sendMessage("Fehler beim holen der Kurse!")
                    return True
		if args[0].decode() == 'list':
			sender.sendMessage("BTC: {}".format(cdata.getBTC()))
			sender.sendMessage("LTC: {}".format(cdata.getLTC()))
			sender.sendMessage("ETH: {}".format(cdata.getETH()))
			sender.sendMessage("IOTA: {}".format(cdata.getIOTA()))
			sender.sendMessage("DOGE: {}".format(cdata.getDOGE()))
		elif args[0].decode() == 'buy':
			pass
		elif args[0].decode() == 'sell':
			pass
		elif args[0].decode() == 'status':
			pass
		#d = crypto.getLiveData()
		return True
