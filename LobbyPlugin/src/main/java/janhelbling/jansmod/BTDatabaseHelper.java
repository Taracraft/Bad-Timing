package janhelbling.jansmod;

import org.bukkit.command.CommandSender;

import java.sql.*;

public class BTDatabaseHelper {
    private Connection con = null;
    private Statement stmt = null;
    CommandSender sender = null;
    BTDatabaseHelper(CommandSender sender){
        this.sender = sender;
        try {
            this.con = DriverManager.getConnection("jdbc:mysql://localhost:3306/bt_navigation_?user=mc&password=X3LyjrmFfL5QLjd9");
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

    void install() throws SQLException {
        /*
         Warp-Locations
         */
        this.stmt.execute("DROP TABLE IF EXISTS warp;");
        this.stmt.execute("CREATE TABLE IF NOT EXISTS warp(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,location TEXT);");
        this.stmt.execute("INSERT INTO warp(location) VALUES(\"Lobby\"),(\"Farmwelt\"),(\"CityBuild\"),(\"Bank\"),(\"Shops\"),(\"CShop\"),(\"Gasthaus\"),(\"XP-Farm\"),(\"End-XP-Farm\"),(\"End\"),(\"Nether\"),(\"4-Gewinnt\");");
    }
}
