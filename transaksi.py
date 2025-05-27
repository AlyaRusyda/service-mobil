from db import get_connection
from datetime import datetime

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT t.id_transaksi, m.no_polisi, t.tanggal_transaksi, t.total_biaya
        FROM transaksi t
        JOIN mobil m ON t.id_mobil = m.id_mobil
    """)
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT t.id_transaksi, m.no_polisi, t.tanggal_transaksi, t.total_biaya
        FROM transaksi t
        JOIN mobil m ON t.id_mobil = m.id_mobil
    """)
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def add():
    id_mobil = input("ID Mobil: ")
    tanggal = datetime.today().strftime('%Y-%m-%d')  # Mengambil tanggal hari ini

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute(
        "INSERT INTO transaksi (id_mobil, tanggal_transaksi, total_biaya) VALUES (%s, %s, 0)",
        (id_mobil, tanggal)
    )
    id_transaksi = cursor.lastrowid

    total_biaya = 0
    while True:
        id_layanan = input("ID Layanan (kosongkan untuk selesai): ")
        if id_layanan == '':
            break
        jumlah = int(input("Jumlah: "))

        cursor.execute("SELECT harga FROM jenis_layanan WHERE id_layanan = %s", (id_layanan,))
        hasil = cursor.fetchone()
        if not hasil:
            print("ID layanan tidak ditemukan.")
            continue
        harga = hasil[0]
        subtotal = harga * jumlah

        total_biaya += subtotal
        cursor.execute("""
            INSERT INTO detail_transaksi (id_transaksi, id_layanan, jumlah, subtotal)
            VALUES (%s, %s, %s, %s)
        """, (id_transaksi, id_layanan, jumlah, subtotal))

    cursor.execute("UPDATE transaksi SET total_biaya = %s WHERE id_transaksi = %s", (total_biaya, id_transaksi))
    conn.commit()
    print("Transaksi berhasil ditambahkan.")
    cursor.close()
    conn.close()
