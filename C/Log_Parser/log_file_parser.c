#include <stdio.h>
#include <stdlib.h>
#include <string.h>

int main(int argc, char *argv[]) {
    //    التحقق من ادخال الملف والكلمة المفتاحية
    if (argc < 3) {
        printf("Usage: %s <log_file> <keyword>\n", argv[0]);
        printf("Example: %s auth.log FAILED\n", argv[0]);
        return 1;
    }
    // تخزين اسم الملف والكلمة المفتاحية
    const char *filename = argv[1];
    const char *keyword = argv[2];

    //    فتح الملف في وضع القراءة  
    FILE *file = fopen(filename, "r");
    if (file == NULL) {  
        printf("Error: Could not open file '%s'. File not found or access denied.\n", filename);
        return 1;
    }

    //   قراءة سطر واحد في كل دورة
    char line[512];
    int line_number = 0;
    int alert_count = 0;

    printf("\n======================================================\n");
    printf("         SOC Security Log Analysis Report             \n");
    printf("======================================================\n");
    printf("Target File    : %s\n", filename);
    printf("Search Keyword : '%s'\n", keyword);
    printf("------------------------------------------------------\n");
    printf("MATCHED EVENTS:\n\n");

    //    قراءة الملف سطرا بسطر باستخدام fgets
    while (fgets(line, sizeof(line), file) != NULL) {
        line_number++;

        //    البحث عن الكلمة المفتاحية داخل السطر الحالي
        if (strstr(line, keyword) != NULL) {
            alert_count++;
            
            line[strcspn(line, "\r\n")] = '\0';

            // طباعة رقم السطر ومحتواه
            printf("[Line %04d] %s\n", line_number, line);
        }
    }

    //    عرض التقرير والملخص النهائي
    printf("\n------------------------------------------------------\n");
    printf("SUMMARY:\n");
    printf("Total Lines Scanned : %d\n", line_number);
    printf("Alerts Detected     : %d\n", alert_count);
    printf("======================================================\n\n");

    //    إغلاق الملف بأمان
    fclose(file);
    return 0;
}