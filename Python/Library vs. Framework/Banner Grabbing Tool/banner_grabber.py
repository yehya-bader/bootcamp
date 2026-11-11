import socket

def grab_banner(target, ip, port):
    s = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    s.settimeout(2.5)
    
    try:
        s.connect((ip, port))
        
        # إرسال طلب أولي لمنافذ الويب
        if port in [80, 8080]:
            request = f"HEAD / HTTP/1.1\r\nHost: {target}\r\nConnection: close\r\n\r\n"
            s.sendall(request.encode())
            
        response = s.recv(1024)
        
        if response:
            text = response.decode('utf-8', errors='ignore')
            lines = text.splitlines()
            # استخراج أول سطر غير فارغ من البانر
            for line in lines:
                if line.strip():
                    return line.strip()
                
    except Exception:
        return None
    finally:
        s.close()
        
    return None

def main():
    target = input("Enter target IP or domain: ").strip()
    
    try:
        target_ip = socket.gethostbyname(target)
    except:
        print("Invalid Target!")
        return

    ports_to_scan = [21, 22, 23, 25, 80, 110, 143, 8080]
    
    print(f"\nGrabbing banners for {target_ip}...")
    
    for port in ports_to_scan:
        banner = grab_banner(target, target_ip, port)
        if banner:
            print(f"Port {port}: {banner}")
        else:
            print(f"Port {port}: No Banner / Closed")

if __name__ == "__main__":
    main()