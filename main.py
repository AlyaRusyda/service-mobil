import mobil
import layanan
import transaksi

def menu():
    while True:
        print("\n=== APLIKASI SERVICE MOBIL ===")
        print("1. Kelola Data Customer")
        print("2. Kelola Data Mobil")
        print("3. Kelola Data Layanan")
        print("4. Transaksi Service")
        print("0. Keluar")
        pilihan = input("Pilih: ")

        if pilihan == '1':
            menu_customer()
        elif pilihan == '2':
            menu_layanan()
        elif pilihan == '3':
            menu_transaksi()
        elif pilihan == '0':
            break
        else:
            print("Pilihan tidak valid!")

def menu_customer():
    while True:
        print("\n--- MENU CUSTOMER ---")
        print("1. Lihat Customer")
        print("2. Tambah Customer")
        print("3. Ubah Customer")
        print("4. Hapus Customer")
        print("0. Kembali")
        pilihan = input("Pilih: ")

        if pilihan == '1':
            mobil.select()
        elif pilihan == '2':
            mobil.add()
        elif pilihan == '3':
            mobil.update()
        elif pilihan == '4':
            mobil.delete()
        elif pilihan == '0':
            break

def menu_mobil():
    while True:
        print("\n--- MENU MOBIL ---")
        print("1. Lihat Mobil")
        print("2. Tambah Mobil")
        print("3. Ubah Mobil")
        print("4. Hapus Mobil")
        print("0. Kembali")
        pilihan = input("Pilih: ")

        if pilihan == '1':
            mobil.select()
        elif pilihan == '2':
            mobil.add()
        elif pilihan == '3':
            mobil.update()
        elif pilihan == '4':
            mobil.delete()
        elif pilihan == '0':
            break

def menu_layanan():
    while True:
        print("\n--- MENU LAYANAN ---")
        print("1. Lihat Layanan")
        print("2. Tambah Layanan")
        print("3. Ubah Layanan")
        print("4. Hapus Layanan")
        print("0. Kembali")
        pilihan = input("Pilih: ")

        if pilihan == '1':
            layanan.select()
        elif pilihan == '2':
            layanan.add()
        elif pilihan == '3':
            layanan.update()
        elif pilihan == '4':
            layanan.delete()
        elif pilihan == '0':
            break

def menu_transaksi():
    while True:
        print("\n--- MENU TRANSAKSI ---")
        print("1. Lihat Transaksi")
        print("2. Tambah Transaksi")
        print("0. Kembali")
        pilihan = input("Pilih: ")

        if pilihan == '1':
            transaksi.select()
        elif pilihan == '2':
            transaksi.add()
        elif pilihan == '0':
            break

if __name__ == '__main__':
    menu()
