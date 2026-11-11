import hashlib
import time
import os

def calculate_file_hash(file_path):
    sha256 = hashlib.sha256()
    try:
        with open(file_path, "rb") as f:
            while True:
                chunk = f.read(4096)
                if not chunk:
                    break
                sha256.update(chunk)
        return sha256.hexdigest()
    except FileNotFoundError:
        return None

def monitor_file(file_path, interval=3):
    if not os.path.exists(file_path):
        print(f"Error: File '{file_path}' not found.")
        return

    baseline_hash = calculate_file_hash(file_path)
    print(f"Monitoring: {file_path}")
    print(f"Baseline Hash: {baseline_hash}")
    print(f"Checking every {interval} seconds...\n")

    try:
        while True:
            time.sleep(interval)
            current_hash = calculate_file_hash(file_path)

            if current_hash is None:
                print("[ALERT] Target file was deleted or moved!")
                break

            if current_hash != baseline_hash:
                print("\n[CRITICAL ALERT] File has been modified!")
                print(f"Old Hash: {baseline_hash}")
                print(f"New Hash: {current_hash}\n")
                baseline_hash = current_hash
            else:
                print("Checking... File is intact.")

    except KeyboardInterrupt:
        print("\nMonitoring stopped.")

if __name__ == "__main__":
    file_to_watch = input("Enter file path to monitor: ").strip()
    monitor_file(file_to_watch)