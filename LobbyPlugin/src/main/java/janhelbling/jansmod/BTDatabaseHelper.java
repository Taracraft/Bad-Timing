package janhelbling.jansmod;

import java.sql.*;

public class BTDatabaseHelper {
    private Connection con = null;
    private Statement stmt = null;
    BTDatabaseHelper(){
        try {
            this.con = DriverManager.getConnection("jdbc:mysql://localhost/a?user=root&password=password");
            this.stmt = this.con.createStatement();
        } catch (SQLException e){
            this.con = null;
        }
    }

    void close(){
        try {
            this.con.close();
        } catch (SQLException e){

        }
    }

    void commit(){
        try {
            this.con.commit();
        } catch (SQLException e){

        }
    }

    void exec(String sql) throws SQLException {
        this.stmt.execute(sql);
    }
    ResultSet query(String sql){
            try{
                return this.stmt.executeQuery(sql);
            } catch (SQLException e){
                return null;
            }
    }

    void install() throws SQLException {
        this.stmt.execute("DROP TABLE IF EXISTS warp;");
        this.stmt.execute("CREATE TABLE IF NOT EXISTS warp(location TEXT, enabled BOOLEAN, x DOUBLE DEFAULT NULL, y DOUBLE DEFAULT NULL, z DOUBLE DEFAULT NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Lobby\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Farmwelt\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"CityBuild\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Bank\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Shops\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"CShop\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Gasthaus\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"XP-Farm\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"End-XP-Farm\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"End\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"Nether\",true,NULL,NULL,NULL);");
        this.stmt.execute("INSERT INTO warp VALUES(\"4-Gewinnt\",true,NULL,NULL,NULL);");
    }
}
