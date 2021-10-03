package janhelbling.jansmod ;

import org.bukkit.command.CommandSender;

import java.sql.*;

public class GeldDatabaseHelper {
    private Connection con = null;
    private Statement stmt = null;
    CommandSender sender = null;
    GeldDatabaseHelper(CommandSender sender){
        this.sender = sender;
        try {
            this.con = DriverManager.getConnection("jdbc:mysql://localhost:3306/bt_?user=root&password=password");
            this.stmt = this.con.createStatement();
        } catch (SQLException e){
            if(this.sender.isOp()) {
                this.sender.sendMessage("Fehler bei MySQL-Verbindung: " + e.getMessage().toString());
            }
            this.sender.getServer().getLogger().info("Fehler bei MySQL-Verbindung: " +e.getMessage().toString());
        }
    }

    void close() {
        try {
            this.con.close();
        } catch (SQLException e) {
            if (this.sender.isOp()) {
                this.sender.sendMessage("Fehler bei schliessung der MySQL-Verbindung: " + e.getMessage().toString());
            }
            this.sender.getServer().getLogger().info("Fehler bei MySQL-Verbindung: " + e.getMessage().toString());
        }
    }

    ResultSet query(String sql){
        try{
            return this.stmt.executeQuery(sql);
        } catch (SQLException e){
            if(this.sender.isOp()){
                this.sender.sendMessage("Fehler bei MySQL-Query: " + e.getMessage().toString());
            }
            this.sender.getServer().getLogger().info("Fehler bei MySQL-Query: " +e.getMessage().toString());
            return null;
        }
    }

    boolean PlayerExists(String id){
        try {
            ResultSet resultSet = this.stmt.executeQuery("SELECT * FROM geld WHERE playerid=\"" + this.sqlisecure(id) + "\";");
            if(resultSet.next()){
                return true;
            }
        } catch (SQLException e) {
            if (this.sender.isOp()) {
                this.sender.getServer().getLogger().info("Fehler bei MySQL-Query: " + e.getMessage().toString());
            }
            this.sender.getServer().getLogger().info("Fehler bei MySQL-Query: " +e.getMessage().toString());
        }
        return false;
    }

    String sqlisecure(String v){
        return v.replace("\"","\\\"").replace("\'","\\\'");
    }

    void addGeld(float money) throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT saldo FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        float geld = resultSet.getFloat("saldo");
        geld += money;
        this.stmt.execute("UPDATE geld SET saldo=\""+String.valueOf(geld)+"\" WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
    }

    boolean removeGeld(float money) throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT saldo FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        float geld = resultSet.getFloat("saldo");
        geld -= money;
        if(geld >= 0) {
            this.stmt.execute("UPDATE geld SET saldo=\"" + String.valueOf(geld) + "\" WHERE playerid=\"" + this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString()) + "\";");
            return true;
        }
        return false;
    }


    void addBankGeld(float money) throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT bank FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        float geld = resultSet.getFloat("bank");
        geld += money;
        this.stmt.execute("UPDATE geld SET bank=\""+String.valueOf(geld)+"\" WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
    }
    boolean removeBankGeld(float money) throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT bank FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        float geld = resultSet.getFloat("bank");
        geld -= money;
        if(geld >= 0) {
            this.stmt.execute("UPDATE geld SET bank =\"" + String.valueOf(geld) + "\" WHERE playerid=\"" + this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString()) + "\";");
            return true;
        }
        return false;
    }
    float getGeld() throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT saldo FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        return resultSet.getFloat("saldo");
    }

    float getBankGeld() throws SQLException {
        ResultSet resultSet = this.stmt.executeQuery("SELECT bank FROM geld WHERE playerid=\""+this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())+"\";");
        resultSet.next();
        return resultSet.getFloat("bank");
    }

    void createuser() throws SQLException {
        /*
         Warp-Locations
         */

        if(!this.PlayerExists(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString())){
            this.stmt.execute("INSERT INTO geld(playerid,saldo,bank) VALUES(\"" + this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString()) + "\",200.00,0.00);");
            this.stmt.execute("INSERT INTO krypto (playerid,kaufpreis_total,btc) VALUES(\"" + this.sqlisecure(this.sender.getServer().getPlayer(this.sender.getName()).getUniqueId().toString()) + "\",0.00,0.00);");
        }
    }
}
