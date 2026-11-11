import socket
import time
from concurrent.futures import ThreadPoolExecutor

def scan_port(target_ip, port):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(1.0)
    try:
        if s.connect_ex((target_ip, port)) == 0:
            print(f"Port {port} is OPEN")
    except:
        pass
    finally:
        s.close()

def main():
    target = input("Enter target IP: ")
    
    start_time = time.time()
    print(f"Scanning {target} from port 1 to 1024...")

    with ThreadPoolExecutor(max_workers=50) as executor:
        for port in range(1, 1025):
            executor.submit(scan_port, target, port)

    total_time = time.time() - start_time
    print(f"Scan finished in {total_time:.2f} seconds")

if __name__ == "__main__":
    main()