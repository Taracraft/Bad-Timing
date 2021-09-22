# Bad-Timing
## MySQL-Verbindung in Jython:
```python
from com.ziclix.python.sql import zxJDBC

connectionUrl = "jdbc:mysql://localhost:3307"
cnxn = zxJDBC.connect(
        connectionUrl,
        "root",
        "mypassword",
        "com.mysql.jdbc.Driver")
crsr = cnxn.cursor()
crsr.execute("SHOW DATABASES")
rows = crsr.fetchall()
print(rows)
```
