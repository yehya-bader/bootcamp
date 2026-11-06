#include <stdio.h>
#include <string.h>
#include <ctype.h>
#include <stdbool.h>

void evaluate_password(const char *password) {
     //يحل مشكلة عدم انهيار البرنامج اذا تم استدعاء الدالة بقيمة فارغة 
    if (password == NULL) {
        printf("Error: Invalid input.\n");
        return;
    }
    //لحساب طول كلمة المرور
    size_t length = strlen(password);
    // شروط كلمة المرور
    bool has_upper = false;
    bool has_lower = false;
    bool has_digit = false;
    bool has_special = false;
    bool is_length_ok = (length >= 8);

    // فحص كل حرف داخل كلمة المرور
    for (size_t i = 0; i < length; i++) {
        unsigned char c = (unsigned char)password[i];

        if (isupper(c)) {
            has_upper = true;
        } else if (islower(c)) {
            has_lower = true;
        } else if (isdigit(c)) {
            has_digit = true;
         //يفحص علامات الترقيم والرمز هذا يحل مشكلة المسافة الفارغة او احرف غير الانجليزية 
        } else if (ispunct(c) || !isalnum(c)) { 
            has_special = true;
        }
    }

    // حساب الشروط التي تحققت
    int score = is_length_ok + has_upper + has_lower + has_digit + has_special;

    // طباعة التقرير التفصيلي
    printf("\n========================================\n");
    printf("        Password Security Report        \n");
    printf("========================================\n");
    printf("Password Length    : %zu characters\n", length);
    printf("Length (>= 8)      : %s\n", is_length_ok ? "[PASS]" : "[FAIL]");
    printf("Uppercase Letter   : %s\n", has_upper    ? "[PASS]" : "[FAIL]");
    printf("Lowercase Letter   : %s\n", has_lower    ? "[PASS]" : "[FAIL]");
    printf("Numbers (Digits)   : %s\n", has_digit    ? "[PASS]" : "[FAIL]");
    printf("Special Symbols    : %s\n", has_special  ? "[PASS]" : "[FAIL]");
    printf("----------------------------------------\n");
    printf("Total Score        : %d / 5\n", score);

    //  التقييم النهائي لكلمة المرور المدخلة
    printf("Verdict            : ");
    if (!is_length_ok) {
        printf("WEAK (Length must be at least 8 characters)\n");
    } else if (score >= 4) {
        printf("STRONG\n");
    } else {
        printf("MEDIUM / ACCEPTABLE\n");
    }
    printf("========================================\n\n");
}

int main() {
    // مصفوفة تتسع لـ 99 حرفاً + خانة الـ Null Terminator '\0'
    char password[100];

    printf("Enter password to test: ");

    // إدخال آمن يمنع Buffer Overflow ويتحقق من نجاح القراءة
    if (scanf("%99s", password) == 1) {
        evaluate_password(password);
    } else {
        printf("Failed to read input.\n");
        return 1;
    }

    return 0;
}