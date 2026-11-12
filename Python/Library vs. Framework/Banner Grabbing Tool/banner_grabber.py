import socket

def get_banner(target, ip, port):
    try:
        with socket.socket(socket.AF_INET, socket.SOCK_STREAM) as s:
            s.settimeout(2.5)
            s.connect((ip, port))

            if port in [80, 8000, 8080]:
                msg = f"HEAD / HTTP/1.1\r\nHost: {target}\r\nConnection: close\r\n\r\n"
                s.sendall(msg.encode())

            data = s.recv(1024)

            if data:
                text = data.decode("utf-8", errors="ignore")
                for line in text.splitlines():
                    if line.strip():
                        return line.strip()

    except Exception:
        return None
        
    return None

def main():
    target = input("Enter target IP or domain: ").strip()

    try:
        ip = socket.gethostbyname(target)
    except socket.gaierror:
        print("Invalid target.")
        return

    ports_input = input("Enter ports (comma separated) or press Enter for default: ").strip()

    if ports_input:
        try:
            ports = [int(p.strip()) for p in ports_input.split(",")]
        except ValueError:
            print("Invalid port format.")
            return
    else:
        ports = [21, 22, 23, 25, 80, 110, 143, 443, 8080]

    print(f"\nScanning {ip}...")

    for p in ports:
        result = get_banner(target, ip, p)
        if result:
            print(f"Port {p}: {result}")
        else:
            print(f"Port {p}: Closed / No Banner")

if __name__ == "__main__":
    main()
