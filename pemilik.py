from db import get_connection

def tambah_pemilik():
    nama = input("Nama Pemilik: ")
    no_telepon = input("No Telepon: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO pemilik (nama, no_telepon) VALUES (%s, %s)", (nama, no_telepon))
    conn.commit()
    print("Pemilik berhasil ditambahkan.")
    cursor.close()
    conn.close()

def tampilkan_pemilik():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM pemilik")
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()
