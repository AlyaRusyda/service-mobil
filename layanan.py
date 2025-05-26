from db import get_connection

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM jenis_layanan")
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def add():
    nama = input("Nama Layanan: ")
    harga = float(input("Harga: "))

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        INSERT INTO jenis_layanan (nama_layanan, harga)
        VALUES (%s, %s, %s)
    """, (nama, harga))
    conn.commit()
    print("Layanan berhasil ditambahkan.")
    cursor.close()
    conn.close()

def update():
    select()
    id_layanan = input("ID Layanan yang akan diubah: ")
    nama = input("Nama Layanan baru: ")
    harga = float(input("Harga baru: "))

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        UPDATE jenis_layanan SET nama_layanan = %s, harga = %s WHERE id_layanan = %s
    """, (nama, harga, id_layanan))
    conn.commit()
    print("Layanan berhasil diubah.")
    cursor.close()
    conn.close()

def delete():
    select()
    id_layanan = input("ID Layanan yang akan dihapus: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM jenis_layanan WHERE id_layanan = %s", (id_layanan,))
    conn.commit()
    print("Layanan berhasil dihapus.")
    cursor.close()
    conn.close()
