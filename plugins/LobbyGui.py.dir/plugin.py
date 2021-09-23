# -*- coding: utf-8 -*-

import sys
sys.path.append("./pythonlibs/")

from org.bukkit.event import EventPriority
from org.bukkit.inventory import ItemStack
from org.bukkit.event.player import PlayerJoinEvent, PlayerInteractEvent
from org.bukkit.event.inventory import InventoryClickEvent

WARP_MENUS = ["lobby","farmwelt","4-gewinnt","bank","citybuild","cshop","end","end-xp-farm","gasthaus","nether","shops","xp-farm"]
MENUS = ["kompass","warp"]

class MeinJoinEvent(PythonListener):
    @PythonEventHandler(PlayerJoinEvent,EventPriority.NORMAL)
    def onEvent(self, event):
        if event.getPlayer().getWorld().getName().decode() == 'Lobby' and not event.getPlayer().getInventory().contains(bukkit.Material.COMPASS):
            event.getPlayer().getInventory().addItem(ItemStack(bukkit.Material.COMPASS, 1))
            event.getPlayer().sendMessage("Kompass erhalten!")


class MeinInteractEvent(PythonListener):
    @PythonEventHandler(PlayerInteractEvent, EventPriority.NORMAL)
    def onEvent(self, event):
        if event.getItem().getType() == bukkit.Material.COMPASS:
            inv = event.getPlayer().getServer().createInventory(event.getPlayer(), 27, "kompass")
            test = ItemStack(bukkit.Material.DIAMOND, 1)
            testmeta = test.getItemMeta()
            testmeta.setDisplayName("Warp")
            test.setItemMeta(testmeta)
            inv.addItem(test)
            event.getPlayer().openInventory(inv)

class MeinInventoryEvent(PythonListener):
    @PythonEventHandler(InventoryClickEvent, EventPriority.NORMAL)
    def onEvent(self, event):
        if event.getView().getTitle().decode().lower() in MENUS:
            event.setCancelled(True)
            item = event.getCurrentItem()
            item_name = item.getItemMeta().getDisplayName().decode().lower()
            if item_name == "warp":
                # WARP-MENU
                inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),27,"warp")
                items = []
                for W in WARP_MENUS:
                    itm   = ItemStack(bukkit.Material.GOLD_BLOCK, 1)
                    itm_m = itm.getItemMeta()
                    itm_m.setDisplayName(W)
                    itm.setItemMeta(itm_m)
                    items.append(itm)
                for i in items:
                    inv.addItem(i)
                event.getView().getPlayer().openInventory(inv)
            elif item_name in WARP_MENUS:
                event.getView().getPlayer().sendMessage("WARP NACH {}!!!".format(item_name))
                event.getView().getPlayer().chat("/warp {}".format(item_name))

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
        if command.getName().decode().lower() == 'navigation':
            if sender.getInventory().contains(bukkit.Material.COMPASS):
                sender.sendMessage("Du besitzt bereits ein Kompass!")
            else:
                sender.getInventory().addItem(ItemStack(bukkit.Material.COMPASS, 1))
                sender.sendMessage("Kompass erhalten!")
        return True
