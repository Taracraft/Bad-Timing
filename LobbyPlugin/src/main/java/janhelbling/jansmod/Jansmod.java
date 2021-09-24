package janhelbling.jansmod;

import com.sun.org.apache.xpath.internal.operations.Bool;
import org.bukkit.Location;
import org.bukkit.Material;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.event.EventHandler;
import org.bukkit.event.Listener;
import org.bukkit.event.inventory.InventoryClickEvent;
import org.bukkit.event.player.PlayerInteractEvent;
import org.bukkit.event.player.PlayerJoinEvent;
import org.bukkit.inventory.Inventory;
import org.bukkit.inventory.ItemStack;
import org.bukkit.inventory.meta.ItemMeta;
import org.bukkit.plugin.java.JavaPlugin;

import java.sql.ResultSet;
import java.sql.SQLException;
import java.util.ArrayList;

class PATH {
    private String[] MENUS = {"Kompass","Warp"};
    BTDatabaseHelper btdb = null;
    ArrayList<String> WARP_LOCATIONS = new ArrayList<String>();

    String[] getPaths(){
        return this.MENUS;
    }
    String[] getWarpLocations(){
        try {
            btdb = new BTDatabaseHelper();
            ResultSet rslt = this.btdb.query("SELECT location FROM warp WHERE enabled=true;");
            while(rslt.next()) {
                this.WARP_LOCATIONS.add(rslt.getString("location"));
            }
        } catch (Exception e){
            this.WARP_LOCATIONS.add("Lobby");
        }
        String[] arr = this.WARP_LOCATIONS.toArray(new String[0]);
        return arr;
    }
}

class MeinKompassListener implements Listener {
    @EventHandler
    public void onPlayerJoin(PlayerJoinEvent event){
        if(!event.getPlayer().getInventory().contains(Material.COMPASS)){
            event.getPlayer().getInventory().addItem(new ItemStack(Material.COMPASS));
            event.getPlayer().sendMessage("Kompass hinzugefügt!");
        }
    }
}

class MeinInteractListener implements Listener {
    @EventHandler
    public void onPlayerInteract(PlayerInteractEvent event){
        if(event.getItem() != null && event.getItem().getType() == Material.COMPASS){
            Inventory inv = event.getPlayer().getServer().createInventory(event.getPlayer(),54,"Kompass");
            String[] p = new PATH().getPaths();
            for(String pa : p){
                if(pa.equals("Kompass"))
                    continue;
                ItemStack its = new ItemStack(Material.GOLD_BLOCK,1);
                ItemMeta itm = its.getItemMeta();
                itm.setDisplayName(pa);
                its.setItemMeta(itm);
                inv.addItem(its);
            }
            // event.getPlayer().closeInventory(); // TESTEN!!!
            event.getPlayer().openInventory(inv);
        }
    }
}

class MeinInventoryClickListener implements Listener {
    @EventHandler
    public void onInventoryClick(InventoryClickEvent event){
        PATH p = new PATH();
        for(String pa : p.getPaths()){
            if(event.getView().getTitle().equals(pa) && event.getCurrentItem().getType() == Material.GOLD_BLOCK){
                event.setCancelled(true);
                ItemStack item = event.getCurrentItem();
                String item_name = item.getItemMeta().getDisplayName();
                if(item_name.equals("Warp")){
                    Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Warp");
                    for(String m: p.getWarpLocations()) {
                        ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                        ItemMeta imt = ist.getItemMeta();
                        imt.setDisplayName(m);
                        ist.setItemMeta(imt);
                        inv.addItem(ist);
                    }
                    event.getView().getPlayer().openInventory(inv);
                }
            }
        }
        for(String wpl: p.getWarpLocations()){
            if(event.getCurrentItem().getItemMeta().getDisplayName().equals(wpl) && event.getCurrentItem().getType() == Material.GOLD_BLOCK){
                event.setCancelled(true);
                event.getView().getPlayer().getServer().getPlayer(event.getWhoClicked().getName()).chat("/warp "+wpl);
            }
        }
    }
}

public final class Jansmod extends JavaPlugin {
    @Override
    public boolean onCommand(CommandSender sender, Command command, String label, String[] args) {
        Inventory inv = getServer().getPlayer(sender.getName()).getInventory();
        String cmd = command.getName();
        if (cmd.toLowerCase().equals("navigation")) {
            if (!inv.contains(Material.COMPASS)) {
                inv.addItem(new ItemStack(Material.COMPASS));
                sender.sendMessage("Kompass hinzugefügt!");
            } else {
                sender.sendMessage("Du besitzt bereits ein Kompass!");
            }
        }
        else if(cmd.toLowerCase().equals("setwarp") && args.length == 1){
                BTDatabaseHelper dbh = new BTDatabaseHelper();
                Location loc = sender.getServer().getPlayer(sender.getName()).getLocation();
                try {
                    // ToDo: MySQL-Injection BLOCK!
                    dbh.exec(String.format("INSERT INTO warp VALUES(\"%s\",true,ROUND(%f,2),ROUND(%f,2),ROUND(%f,2));", args[0], loc.getX(), loc.getY(), loc.getZ()));
                } catch (SQLException e){
                    sender.sendMessage(e.getMessage().toString());
                }
        }
        else if(cmd.toLowerCase().equals("install-1337")){
            try {
                BTDatabaseHelper dbh = new BTDatabaseHelper();
                dbh.install();
                dbh.close();
                sender.sendMessage("Database installed/reseted!");
            } catch (SQLException e) {
                sender.sendMessage(e.getMessage().toString());
            }
        }
        else if(cmd.toLowerCase().equals("mysql-test")){
            try {
                BTDatabaseHelper dbh = new BTDatabaseHelper();
                dbh.commit();
                ResultSet r = dbh.query("SELECT location FROM warp;");
                while (r != null && r.next()) {
                    sender.sendMessage(r.getString("location"));
                }
            } catch(SQLException e){
                sender.sendMessage(e.getMessage().toString());
            }
        }
        return true;
    }

    @Override
    public void onEnable() {
        getLogger().info("LobbyPlugin ENABLED!");
        getServer().getPluginManager().registerEvents(new MeinKompassListener(),this);
        getServer().getPluginManager().registerEvents(new MeinInteractListener(), this);
        getServer().getPluginManager().registerEvents(new MeinInventoryClickListener(), this);
    }

    @Override
    public void onDisable() {
        getLogger().info("LobbyPlugin DISABLED!");
    }
}
