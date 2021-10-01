package janhelbling.jansmod;

import java.sql.Connection;
import java.sql.DriverManager;
import java.sql.SQLException;
import java.sql.Statement;

public class InstallDB {
    private Connection con = null;
    private Statement stmt = null;
    void install() throws SQLException {
        /*
         Warp-Locations & GELD
         */
        this.con = DriverManager.getConnection("jdbc:mysql://localhost:3306/bt_?user=root&password=password");
        this.stmt = this.con.createStatement();

        this.stmt.execute("DROP TABLE IF EXISTS warp;");
        this.stmt.execute("CREATE TABLE IF NOT EXISTS warp(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,location TEXT);");
        this.stmt.execute("INSERT INTO warp(location) VALUES(\"Lobby\"),(\"Farmwelt\"),(\"CityBuild\"),(\"Bank\"),(\"Shops\"),(\"CShop\"),(\"Gasthaus\"),(\"XP-Farm\"),(\"End-XP-Farm\"),(\"End\"),(\"Nether\"),(\"4-Gewinnt\");");

        this.stmt.execute("DROP TABLE IF EXISTS geld;");
        this.stmt.execute("DROP TABLE IF EXISTS krypto;");
        this.stmt.execute("CREATE TABLE IF NOT EXISTS geld(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY, playerid TEXT, saldo FLOAT, bank FLOAT);");
        this.stmt.execute("CREATE TABLE IF NOT EXISTS krypto(id INT NOT NULL AUTO_INCREMENT PRIMARY KEY,playerid TEXT, kaufpreis_total FLOAT, btc FLOAT);");

    }
}
