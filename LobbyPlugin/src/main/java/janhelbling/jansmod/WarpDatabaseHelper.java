package janhelbling.jansmod;

import org.bukkit.command.CommandSender;

import java.sql.*;

public class WarpDatabaseHelper {
    private Connection con = null;
    private Statement stmt = null;
    CommandSender sender = null;
    WarpDatabaseHelper(CommandSender sender){
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

    String sqlisecure(String v){
        return v.replace("\"","\\\"").replace("\'","\\\'");
    }
}
