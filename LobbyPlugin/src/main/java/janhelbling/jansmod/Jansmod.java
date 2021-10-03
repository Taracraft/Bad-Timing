package janhelbling.jansmod;

import org.bukkit.Material;
import org.bukkit.command.Command;
import org.bukkit.command.CommandSender;
import org.bukkit.entity.Player;
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
import java.util.Arrays;
import java.util.List;

class PATH {
    private String[] MENUS = {"Kompass","Warp","Geld","Kryptowährungen","Einstellungen"};
    public String[] GELD_OPTS = {"Saldo","Ein/Auszahlen","Geld-Senden","Zurück"};
    public String[] GELD_WERTE = {"10","25","50","100","1000","2500","5000","10000","Zurück"};
    WarpDatabaseHelper btdb = null;
    ArrayList<String> WARP_LOCATIONS = new ArrayList<String>();

    String[] getPaths(){
        return this.MENUS;
    }
    String[] getWarpLocations(CommandSender s){
        try {
            this.btdb = new WarpDatabaseHelper(s);
            ResultSet results = this.btdb.query("SELECT location FROM warp;");
            while(results.next()) {
                this.WARP_LOCATIONS.add(results.getString("location"));
            }
            this.WARP_LOCATIONS.add("Zurück");
        } catch (SQLException e){
            if(s.isOp()){
                s.sendMessage("Fehler bei MySQL-Select " +e.getMessage().toString());
            }
            s.getServer().getLogger().info("Fehler bei MySQL-Select " +e.getMessage().toString());
            this.WARP_LOCATIONS.add("Lobby");
            this.WARP_LOCATIONS.add("Zurück");
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
            event.getPlayer().closeInventory();
            event.getPlayer().openInventory(inv);
        }
    }
}

class MeinInventoryClickListener implements Listener {
    private String old_path = "";
    @EventHandler
    public void onInventoryClick(InventoryClickEvent event){
        PATH p = new PATH();
        for(String pa : p.getPaths()){
            if(event.getCurrentItem().getItemMeta().getDisplayName().equals("Zurück")){
                Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Kompass");
                String[] pt = new PATH().getPaths();
                for(String pat : pt){
                    if(pat.equals("Kompass"))
                        continue;
                    ItemStack its = new ItemStack(Material.GOLD_BLOCK,1);
                    ItemMeta itm = its.getItemMeta();
                    itm.setDisplayName(pat);
                    its.setItemMeta(itm);
                    inv.addItem(its);
                }
                event.getView().getPlayer().closeInventory();
                event.getView().getPlayer().openInventory(inv);
                break;
            }
            else if((event.getView().getTitle().equals(pa) || event.getView().getTitle().contains(this.old_path) ) && event.getCurrentItem().getType() == Material.GOLD_BLOCK){
                event.setCancelled(true);
                ItemStack item = event.getCurrentItem();
                String item_name = item.getItemMeta().getDisplayName();
                Player[] ps = event.getView().getPlayer().getServer().getOnlinePlayers().toArray(new Player[0]);
                if(item_name.equals("Warp")){
                    this.old_path = "Warp";
                    Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Warp");
                    for(String m: p.getWarpLocations(event.getView().getPlayer())) {
                        ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                        ItemMeta imt = ist.getItemMeta();
                        imt.setDisplayName(m);
                        ist.setItemMeta(imt);
                        inv.addItem(ist);
                    }
                    event.getView().getPlayer().openInventory(inv);
                    break;
                } else if(item_name.equals("Geld")){
                    this.old_path = "Geld";
                    Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Geld");
                    for(String m: p.GELD_OPTS) {
                        ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                        ItemMeta imt = ist.getItemMeta();
                        imt.setDisplayName(m);
                        ist.setItemMeta(imt);
                        inv.addItem(ist);
                    }
                    event.getView().getPlayer().openInventory(inv);
                    break;
                } else if(item_name.equals("Saldo") && this.old_path.equals("Geld")){
                    event.getView().getPlayer().closeInventory();
                    GeldDatabaseHelper g = new GeldDatabaseHelper(event.getView().getPlayer());
                    try {
                        g.createuser();
                        float m = g.getGeld();
                        float b = g.getBankGeld();
                        event.getView().getPlayer().sendMessage("GELD: "+String.valueOf(m));
                        event.getView().getPlayer().sendMessage("BANK: "+String.valueOf(b));
                    } catch(SQLException e){
                        event.getView().getPlayer().sendMessage("ERROR "+e.getMessage().toString());
                    }
                    break;
                } else if(item_name.equals("Ein/Auszahlen")){
                    this.old_path = "Ein/Auszahlen";
                    Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Geld");
                    for(String m: p.GELD_WERTE) {
                        ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                        ItemMeta imt = ist.getItemMeta();
                        imt.setDisplayName(m);
                        if(!m.equals("Zurück")) {
                            List<String> l = Arrays.asList(new String[]{"Linksklick: Einzahlen", "Rechtsklick: Auszahlen"});
                            imt.setLore(l);
                        }
                        ist.setItemMeta(imt);
                        inv.addItem(ist);
                    }
                    event.getView().getPlayer().openInventory(inv);
                    break;
                } else if(old_path.equals("Ein/Auszahlen") && (item_name.equals("10") || item_name.equals("25") || item_name.equals("50") || item_name.equals("100") || item_name.equals("1000") || item_name.equals("2500") || item_name.equals("5000") || item_name.equals("10000"))){
                    if(event.getClick().isLeftClick()) {
                        GeldDatabaseHelper geldDatabaseHelper = new GeldDatabaseHelper(event.getView().getPlayer());
                        float b = Float.valueOf(item_name);
                        try {
                            if (geldDatabaseHelper.removeGeld(b)) {
                                geldDatabaseHelper.addBankGeld(b);
                                event.getView().getPlayer().sendMessage(String.valueOf(b) + " auf das Konto eingezahlt!");
                                event.getView().getPlayer().sendMessage("Konto-Saldo: " + String.valueOf(geldDatabaseHelper.getBankGeld()));
                               } else {
                                event.getView().getPlayer().sendMessage("Nicht genug Geld!");
                            }
                        } catch (SQLException e) {
                            if (event.getView().getPlayer().isOp()) {
                                event.getView().getPlayer().sendMessage(e.getMessage().toString());
                            }
                            event.getView().getPlayer().getServer().getLogger().info(e.getMessage().toString());
                        }
                        break;
                    } else if(event.getClick().isRightClick()) {
                        GeldDatabaseHelper geldDatabaseHelper = new GeldDatabaseHelper(event.getView().getPlayer());
                        float b = Float.valueOf(item_name);
                        try {
                            if(geldDatabaseHelper.removeBankGeld(b)){
                                geldDatabaseHelper.addGeld(b);
                                event.getView().getPlayer().sendMessage("Geld ausgezahlt!");
                                event.getView().getPlayer().sendMessage("Konto-Saldo: " + String.valueOf(geldDatabaseHelper.getBankGeld()));
                            } else {
                                event.getView().getPlayer().sendMessage("Nicht genug Geld!");
                            }
                        } catch (SQLException e) {
                            if (event.getView().getPlayer().isOp()) {
                                event.getView().getPlayer().sendMessage(e.getMessage().toString());
                            }
                            event.getView().getPlayer().getServer().getLogger().info(e.getMessage().toString());
                        }
                        break;
                    }
                } else if(item_name.contains("Geld-Senden")){
                    this.old_path = "Geld-Senden";
                    Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Geld-Senden");
                    for(Player pl : ps){
                        if(pl.getName() == event.getView().getPlayer().getName()){
                            continue;
                        }
                        ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                        ItemMeta imt = ist.getItemMeta();
                        imt.setDisplayName(pl.getName());
                        ist.setItemMeta(imt);
                        inv.addItem(ist);
                    }
                    event.getView().getPlayer().openInventory(inv);
                    break;
                } else if(!old_path.contains("Ein/Auszahlen") && (item_name.equals("10") || item_name.equals("25") || item_name.equals("50") || item_name.equals("100") || item_name.equals("1000") || item_name.equals("2500") || item_name.equals("5000") || item_name.equals("10000"))) {
                    CommandSender recv = event.getView().getPlayer().getServer().getPlayerExact(old_path);
                    CommandSender send = event.getView().getPlayer();

                    GeldDatabaseHelper geldDatabaseHelperSender = new GeldDatabaseHelper(send);
                    GeldDatabaseHelper geldDatabaseHelperRecv = new GeldDatabaseHelper(recv);
                    float b = Float.valueOf(item_name);
                    try {
                        if (geldDatabaseHelperSender.getBankGeld() < b) {
                            event.getView().getPlayer().sendMessage("Zu wenig Geld!");
                        } else {
                            geldDatabaseHelperSender.removeBankGeld(b);
                            geldDatabaseHelperRecv.addBankGeld(b);
                            event.getView().getPlayer().sendMessage(String.valueOf(b) + " $ an "+ event.getView().getPlayer().getServer().getPlayer(this.old_path).getName()+" gedendet!");
                            event.getView().getPlayer().getServer().getPlayer(item_name).sendMessage(String.valueOf(b) + " $ von "+ event.getView().getPlayer().getName()+" erhalten!");
                        }
                    } catch (SQLException e ){
                        if(event.getView().getPlayer().isOp()){
                            event.getView().getPlayer().sendMessage(e.getMessage().toString());
                        }
                        event.getView().getPlayer().getServer().getLogger().info(e.getMessage().toString());
                    }
                    break;
                }
                else
                    {
                    this.old_path = "Geld-Senden";
                    for(Player pl : ps){
                        if(item_name.contains(pl.getName())){
                            old_path = pl.getName();
                            Inventory inv = event.getView().getPlayer().getServer().createInventory(event.getView().getPlayer(),54,"Geld");
                            for(String m: p.GELD_WERTE) {
                                ItemStack ist = new ItemStack(Material.GOLD_BLOCK);
                                ItemMeta imt = ist.getItemMeta();
                                imt.setDisplayName(m);
                                ist.setItemMeta(imt);
                                inv.addItem(ist);
                            }
                            event.getView().getPlayer().openInventory(inv);
                        }
                    }
                    break;
                }
            }
        }
        for(String wpl: p.getWarpLocations(event.getView().getPlayer().getServer().getConsoleSender())){
            if(event.getCurrentItem().getItemMeta().getDisplayName().equals(wpl) && !wpl.contains("Zurück") && event.getCurrentItem().getType() == Material.GOLD_BLOCK){
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
        if (cmd.equalsIgnoreCase("navigation")) {
            if (!inv.contains(Material.COMPASS)) {
                inv.addItem(new ItemStack(Material.COMPASS));
                sender.sendMessage("Kompass hinzugefügt!");
            } else {
                sender.sendMessage("Du besitzt bereits ein Kompass!");
            }
        }
        else if(cmd.equalsIgnoreCase("install-warp-db")) {
            try {
                InstallDB dbi = new InstallDB();
                dbi.install();
                sender.sendMessage("Database installed/reseted!");
            } catch (SQLException e) {
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
