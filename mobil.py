from db import get_connection

def select():
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT m.id_mobil, m.no_polisi, m.merk, m.model, c.nama 
        FROM mobil m
        LEFT JOIN customer c ON m.id_customer = c.id_customer
    """)
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def select_by_id(id_mobil):
    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        SELECT m.id_mobil, m.no_polisi, m.merk, m.model, c.nama 
        FROM mobil m
        LEFT JOIN customer c ON m.id_customer = c.id_customer
        WHERE m.id_mobil = %s
    """, (id_mobil,))
    for row in cursor.fetchall():
        print(row)
    cursor.close()
    conn.close()

def add():
    no_polisi = input("No Polisi: ")
    merk = input("Merk: ")
    model = input("Model: ")
    id_customer = input("ID Customer (sudah terdaftar): ")

    conn = get_connection()
    cursor = conn.cursor()
    cursor.execute("""
        INSERT INTO mobil (no_polisi, merk, model, id_customer)
        VALUES (%s, %s, %s, %s)
    """, (no_polisi, merk, model, id_customer))
    conn.commit()
    print("Data mobil berhasil ditambahkan.")
    cursor.close()
    conn.close()

def update():
    select()
    id_mobil = input("\nID Mobil yang akan diubah: ")
    select_by_id(id_mobil)
    print("\nApa yang ingin diubah?")
    print("1. Merk")
    print("2. Model")
    print("3. ID Customer")
    print("4. Semuanya")
    pilihan = input("Pilih (1/2/3/4): ")

    conn = get_connection()
    cursor = conn.cursor()

    if pilihan == '1':
        merk = input("Merk baru: ")
        cursor.execute("UPDATE mobil SET merk = %s WHERE id_mobil = %s", (merk, id_mobil))
    elif pilihan == '2':
        model = input("Model baru: ")
        cursor.execute("UPDATE mobil SET model = %s WHERE id_mobil = %s", (model, id_mobil))
    elif pilihan == '3':
        id_customer = input("ID Customer baru: ")
        cursor.execute("UPDATE mobil SET id_customer = %s WHERE id_mobil = %s", (id_customer, id_mobil))
    elif pilihan == '4':
        merk = input("Merk baru: ")
        model = input("Model baru: ")
        id_customer = input("ID Customer baru: ")
        cursor.execute("""
            UPDATE mobil SET merk = %s, model = %s, id_customer = %s
            WHERE id_mobil = %s
        """, (merk, model, id_customer, id_mobil))
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
