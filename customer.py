from db import get_connection

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM customer")
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def add():
    nama = input("Nama Customer: ")
    no_telepon = input("No Telepon: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("INSERT INTO customer (nama, no_telepon) VALUES (%s, %s)", (nama, no_telepon))
    conn.commit()
    print("Customer berhasil ditambahkan.")
    cursor.close()
    conn.close()

def update():
    select()
    id_customer = input("\nID Customer yang akan diubah: ")
    nama = input("Nama baru: ")
    no_telepon = input("No Telepon baru: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        UPDATE customer SET nama = %s, no_telepon = %s WHERE id_customer = %s
    """, (nama, no_telepon, id_customer))
    conn.commit()
    print("Customer berhasil diubah.")
    cursor.close()
    conn.close()

def delete():
    select()
    id_customer = input("ID Customer yang akan dihapus: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM customer WHERE id_customer = %s", (id_customer,))
    conn.commit()
    print("Customer berhasil dihapus.")
    cursor.close()
    conn.close()
