import os
import re
from collections import Counter

# أنماط الهجمات الشائعة
ATTACK_PATTERNS = {
    "SQL Injection": re.compile(r"(\bUNION\s+SELECT\b|SELECT.+FROM|OR\s+1=1|--)", re.IGNORECASE),
    "Path Traversal": re.compile(r"(\.\./|/etc/passwd)", re.IGNORECASE),
    "XSS": re.compile(r"(<script|javascript:|alert\()", re.IGNORECASE),
    "Command Injection": re.compile(r"(;\s*(cat|ls|id|whoami)|\|\s*(cat|ls))", re.IGNORECASE)
}

IP_PATTERN = re.compile(r"(\d{1,3}\.\d{1,3}\.\d{1,3}\.\d{1,3})")

def analyze_log_file(file_path):
    if not os.path.exists(file_path):
        print(f"Error: file '{file_path}' not found.")
        return

    total_lines = 0
    attack_counts = Counter()

    print(f"Starting log analysis for: {file_path}")
    print("-" * 50)

    with open(file_path, "r", encoding="utf-8", errors="ignore") as file:
        for line_num, line in enumerate(file, 1):
            total_lines += 1
            line = line.strip()

            ip_match = IP_PATTERN.search(line)
            ip = ip_match.group(1) if ip_match else "Unknown"

            for attack_type, pattern in ATTACK_PATTERNS.items():
                if pattern.search(line):
                    attack_counts[attack_type] += 1
                    print(f"[ALERT] {attack_type} detected!")
                    print(f"Line: {line_num} | IP: {ip}")
                    print(f"Log: {line}\n")

    print("-" * 50)
    print("Analysis Summary:")
    print(f"Total lines scanned: {total_lines}")
    print(f"Total attacks found: {sum(attack_counts.values())}")
    for attack, count in attack_counts.items():
        print(f"- {attack}: {count}")

if __name__ == "__main__":
    path = input("Enter log file path: ").strip()
    analyze_log_file(path)