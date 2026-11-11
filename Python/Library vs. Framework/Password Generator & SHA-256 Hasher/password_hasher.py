import hashlib
import secrets
import string

def generate_password(length=12):
    # التأكد من أن الطول كافٍ لتغطية الشروط الأمنية
    if length < 8:
        length = 8

    # ضمان وجود محرف واحد على الأقل من كل فئة أمنية
    password_chars = [
        secrets.choice(string.ascii_uppercase),
        secrets.choice(string.ascii_lowercase),
        secrets.choice(string.digits),
        secrets.choice(string.punctuation)
    ]

    # إكمال باقي الطول المطلوب من جميع الفئات
    all_chars = string.ascii_letters + string.digits + string.punctuation
    for _ in range(length - 4):
        password_chars.append(secrets.choice(all_chars))

    # خلط الأحرف لضمان عدم بقاء الترتيب ثابتاً في البداية
    secrets.SystemRandom().shuffle(password_chars)
    
    return ''.join(password_chars)

def calculate_sha256(text):
    return hashlib.sha256(text.encode()).hexdigest()

def main():
    length = int(input("Enter password length (min 8): "))
    
    password = generate_password(length)
    password_hash = calculate_sha256(password)

    print("\nGenerated Password:", password)
    print("SHA-256 Hash:", password_hash)

if __name__ == "__main__":
    main()