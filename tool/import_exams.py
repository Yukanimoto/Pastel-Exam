import os
import mysql.connector

# Cấu hình DB
DB_CONFIG = {
    "host": "localhost",
    "user": "root",
    "password": "",
    "database": "exam_db"
}

FOLDER_PATH = "../raw_exams"  # đổi theo folder của bạn
DEFAULT_UNI = "Đại học Demo"
DEFAULT_SUBJECT = "Chưa phân loại"
DEFAULT_YEAR = 2024

def main():
    conn = mysql.connector.connect(**DB_CONFIG)
    cursor = conn.cursor()

    if not os.path.isdir(FOLDER_PATH):
        print("Folder không tồn tại:", FOLDER_PATH)
        return

    for filename in os.listdir(FOLDER_PATH):
        if not filename.lower().endswith((".pdf", ".doc", ".docx")):
            continue

        title = os.path.splitext(filename)[0]
        # chuyển file sang thư mục uploads của web
        src = os.path.join(FOLDER_PATH, filename)
        dest_folder = "../uploads"
        os.makedirs(dest_folder, exist_ok=True)
        dest = os.path.join(dest_folder, filename)

        # copy file
        if not os.path.exists(dest):
            with open(src, "rb") as fsrc, open(dest, "wb") as fdst:
                fdst.write(fsrc.read())

        file_path = "uploads/" + filename

        sql = """
        INSERT INTO exams (title, university, subject, year, file_path)
        VALUES (%s, %s, %s, %s, %s)
        """
        cursor.execute(sql, (title, DEFAULT_UNI, DEFAULT_SUBJECT, DEFAULT_YEAR, file_path))
        print("Đã thêm:", title)

    conn.commit()
    cursor.close()
    conn.close()
    print("Hoàn thành import.")

if __name__ == "__main__":
    main()
