# -*- coding: utf-8 -*-

from org.bukkit.event import EventPriority
from org.bukkit.inventory import ItemStack
from org.bukkit.event.player import PlayerJoinEvent,PlayerInteractEvent
from org.bukkit.event.inventory import InventoryClickEvent

class MeinJoinEvent(PythonListener):
    @PythonEventHandler(PlayerJoinEvent, EventPriority.NORMAL)  # Hier kommt das Event
    def onEvent(self, event):
        # loc = event.getSpawnLocation()  # location
        weltname = event.getPlayer().getWorld().getName().decode()  # weltname
        if weltname == 'Lobby':
            event.getPlayer().getInventory().addItem(ItemStack(bukkit.Material.COMPASS, 1))
            #event.getPlayer().setCompassTarget(location)

class MeinInteractEvent(PythonListener):
    @PythonEventHandler(PlayerInteractEvent, EventPriority.NORMAL)  # Hier kommt das Event
    def onEvent(self, event):
        if event.getItem().getType() == bukkit.Material.COMPASS:
            # loc = event.getSpawnLocation()  # location
            weltname = event.getPlayer().getWorld().getName().decode()  # weltname
            if weltname == 'Lobby':
                inv = event.getPlayer().getServer().createInventory(event.getPlayer(), 27, "KOMPASS")
                test = ItemStack(bukkit.Material.DIAMOND, 1)
                testmeta = test.getItemMeta()
                testmeta.setDisplayName("Menu Eintrag #1")
                test.setItemMeta(testmeta)
                inv.addItem(test)
                event.getPlayer().openInventory(inv)
                #event.getPlayer().setCompassTarget(location)

class MeinInventoryEvent(PythonListener):
        @PythonEventHandler(InventoryClickEvent, EventPriority.NORMAL)
        def onEvent(self, event):
            weltname = event.getView().getPlayer().getWorld().getName().decode()
            if weltname == 'Lobby':
    	        item = event.getCurrentItem()
	        if item.getType() == bukkit.Material.DIAMOND:
	            event.setCancelled(True)
	            event.getView().getPlayer().sendMessage("OPTION #1 GEKLICKT!")
	            #event.getPlayer().setCompassTarget(location)

class LobbyGUI(PythonPlugin):
    def onEnable(self):
        self.getLogger().info("Lobby-GUI enabled!")
        self.pm = self.getServer().getPluginManager()
        self.lst1 = MeinJoinEvent()
        self.lst2 = MeinInteractEvent()
        self.lst3 = MeinInventoryEvent()
        self.pm.registerEvents(self.lst1, self)
        self.pm.registerEvents(self.lst2, self)
        self.pm.registerEvents(self.lst3, self)
    def onDisable(self):
        self.getLogger().info("Lobby-GUI disabled!")
