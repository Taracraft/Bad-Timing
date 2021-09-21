# -*- coding: utf-8 -*-

from org.bukkit.event import EventPriority
from org.bukkit.inventory import ItemStack
from org.bukkit.event.player import PlayerJoinEvent, PlayerInteractEvent
from org.bukkit.event.inventory import InventoryClickEvent


class MeinJoinEvent(PythonListener):
    @PythonEventHandler(PlayerJoinEvent,
                        EventPriority.NORMAL)  # Hier kommt das Event
    def onEvent(self, event):
        # loc = event.getSpawnLocation()  # location
        if event.getPlayer().getWorld().getName().decode() == 'Lobby' and not event.getPlayer().getInventory().contains(bukkit.Material.COMPASS):
            event.getPlayer().getInventory().addItem(ItemStack(bukkit.Material.COMPASS, 1))
            event.getPlayer().sendMessage("Kompass erhalten!")
            # event.getPlayer().setCompassTarget(location)
        pass


class MeinInteractEvent(PythonListener):
    @PythonEventHandler(PlayerInteractEvent,
                        EventPriority.NORMAL)  # Hier kommt das Event
    def onEvent(self, event):
        if event.getItem().getType() == bukkit.Material.COMPASS:
            # loc = event.getSpawnLocation()  # location
            inv = event.getPlayer().getServer().createInventory(event.getPlayer(), 27, "KOMPASS")
            test = ItemStack(bukkit.Material.DIAMOND, 1)
            testmeta = test.getItemMeta()
            testmeta.setDisplayName("Menu Eintrag #1")
            test.setItemMeta(testmeta)
            inv.addItem(test)
            event.getPlayer().openInventory(inv)
            # event.getPlayer().setCompassTarget(location)
        pass


class MeinInventoryEvent(PythonListener):
    @PythonEventHandler(InventoryClickEvent, EventPriority.NORMAL)
    def onEvent(self, event):
        if event.getView().getTitle().decode().lower() in ["kompass","warp"]:
            item = event.getCurrentItem()
            if item.getType() == bukkit.Material.DIAMOND:
                event.setCancelled(True)
                event.getView().getPlayer().sendMessage("OPTION #1 GEKLICKT!")
                inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),27,"WARP")
                test = ItemStack(bukkit.Material.GOLD_BLOCK, 1)
                testmeta = test.getItemMeta()
                testmeta.setDisplayName("Lobby")
                test.setItemMeta(testmeta)
                inv.addItem(test)
                event.getView().getPlayer().openInventory(inv)
            elif item.getType() == bukkit.Material.GOLD_BLOCK:
                event.setCancelled(True)
                event.getView().getPlayer().sendMessage("WARP NACH LOBBY!!!")
                event.getView().getPlayer().chat("/warp Lobby")
        pass

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
    
    def onCommand(self, sender, command, label, args):
        if sender.getInventory().contains(bukkit.Material.COMPASS):
            sender.sendMessage("Du besitzt bereits ein Kompass!")
        else:
            sender.getInventory().addItem(ItemStack(bukkit.Material.COMPASS, 1))
            sender.sendMessage("Kompass erhalten!")
        return True
