import sqlite3

def conectar():
    conn = sqlite3.connect("contactos.db")
    conn.execute("""CREATE TABLE IF NOT EXISTS contactos (
        id INTEGER PRIMARY KEY AUTOINCREMENT,
        nombre TEXT NOT NULL,
        telefono TEXT,
        email TEXT
    )""")
    conn.commit()
    return conn

def agregar(conn, nombre, telefono, email):
    conn.execute("INSERT INTO contactos (nombre, telefono, email) VALUES (?, ?, ?)",
                 (nombre, telefono, email))
    conn.commit()

def listar(conn):
    return conn.execute("SELECT * FROM contactos").fetchall()

def buscar(conn, nombre):
    return conn.execute("SELECT * FROM contactos WHERE nombre LIKE ?",
                        (f"%{nombre}%",)).fetchall()

def eliminar(conn, id):
    conn.execute("DELETE FROM contactos WHERE id = ?", (id,))
    conn.commit()

def main():
    conn = conectar()
    print("=== Gestor de Contactos ===")
    while True:
        print("\n1. Agregar  2. Listar  3. Buscar  4. Eliminar  5. Salir")
        match input("Opción: "):
            case '1':
                agregar(conn, input("Nombre: "), input("Teléfono: "), input("Email: "))
                print("Contacto agregado.")
            case '2':
                for c in listar(conn):
                    print(c)
            case '3':
                for c in buscar(conn, input("Buscar: ")):
                    print(c)
            case '4':
                eliminar(conn, int(input("ID: ")))
                print("Eliminado.")
            case '5':
                break
    conn.close()

if __name__ == "__main__":
    main()
