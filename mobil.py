from db import get_connection

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("SELECT * FROM mobil")
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def add():
    no_polisi = input("No Polisi: ")
    merk = input("Merk: ")
    model = input("Model: ")
    nama_pemilik = input("Nama Pemilik: ")
    no_telepon = input("No Telepon: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        INSERT INTO mobil (no_polisi, merk, model, nama_pemilik, no_telepon)
        VALUES (%s, %s, %s, %s, %s, %s)
    """, (no_polisi, merk, model, nama_pemilik, no_telepon))
    conn.commit()
    print("Data mobil berhasil ditambahkan.")
    cursor.close()
    conn.close()

from db import get_connection
from mobil import tampilkan_mobil  # Pastikan fungsi tampilkan_mobil() sudah dibuat

def update():
    tampilkan_mobil()
    id_mobil = input("\nID Mobil yang akan diubah: ")

    print("\nApa yang ingin diubah?")
    print("1. Merk")
    print("2. Tipe")
    print("3. No Telepon")
    print("4. Semuanya")
    pilihan = input("Pilih (1/2/3/4): ")

    conn = get_connection()
    cursor = conn.cursor()

    if pilihan == '1':
        merk = input("Merk baru: ")
        cursor.execute("UPDATE mobil SET merk = %s WHERE id_mobil = %s", (merk, id_mobil))
    elif pilihan == '2':
        tipe = input("Tipe baru: ")
        cursor.execute("UPDATE mobil SET model = %s WHERE id_mobil = %s", (tipe, id_mobil))
    elif pilihan == '3':
        no_telepon = input("No Telepon baru: ")
        cursor.execute("UPDATE mobil SET no_telepon = %s WHERE id_mobil = %s", (no_telepon, id_mobil))
    elif pilihan == '4':
        merk = input("Merk baru: ")
        tipe = input("Tipe baru: ")
        no_telepon = input("No Telepon baru: ")
        cursor.execute("""
            UPDATE mobil SET merk = %s, model = %s, no_telepon = %s WHERE id_mobil = %s
        """, (merk, tipe, no_telepon, id_mobil))
    else:
        print("Pilihan tidak valid!")
        cursor.close()
        conn.close()
        return

    conn.commit()
    print("Data mobil berhasil diubah.")
    cursor.close()
    conn.close()

def delete():
    select()
    id_mobil = input("ID Mobil yang akan dihapus: ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("DELETE FROM mobil WHERE id_mobil = %s", (id_mobil,))
    conn.commit()
    print("Data mobil berhasil dihapus.")
    cursor.close()
    conn.close()
