#include <stdio.h>
#include <stdlib.h>

int main(int argc, char *argv[]) {
    //  التحقق من تمرير اسم الملف في سطر الاوامر
    if (argc < 2) {
        printf("Usage: %s <filename>\n", argv[0]);
        return 1;
    }

    // 2. فتح الملف في وضعية القراءة "Binary" 
    FILE *file = fopen(argv[1], "rb");
    if (file == NULL) {
        printf("Error: Could not open file '%s'. File not found or access denied.\n", argv[1]);
        return 1;
    }
  
    // مصفوفة لتخزين 16 بايت في كل دورة
    unsigned char buffer[16];
    size_t bytes_read;
    unsigned int offset = 0; 
    
    printf("\nOffset(h)  -- 00 01 02 03 04 05 06 07 08 09 0A 0B 0C 0D 0E 0F --  Decoded Text\n");
    printf("-----------------------------------------------------------------------------\n");

    // قراءة الملف بمقدار 16 بايت في كل دورة حتى نهاية الملف
    while ((bytes_read = fread(buffer, 1, sizeof(buffer), file)) > 0) {
        
        // طباعة موقع المؤشر الحالي في الملف بالـ Hex
        printf("%08X   ", offset);

        //   طباعة قيم البايتات بصيغة الـ Hex
        for (size_t i = 0; i < bytes_read; i++) {
            printf("%02X ", buffer[i]);
        }

        //اضافة مسافات للمحاذاة
        for (size_t i = bytes_read; i < 16; i++) {
            printf("   ");
        }

        printf(" | ");

        //    طباعة ASCII
        for (size_t i = 0; i < bytes_read; i++) {
            if (buffer[i] >= 32 && buffer[i] <= 126) {
                printf("%c", buffer[i]); // حرف قابل للطباعة
            } else {
                printf(".");            // استبدال الرموز غير القابلة للطباعة بنقطة
            }
        }

        printf("\n");
        offset += bytes_read;
    }

    printf("-----------------------------------------------------------------------------\n\n");

    //    إغلاق الملف بأمان
    fclose(file);
    return 0;
}