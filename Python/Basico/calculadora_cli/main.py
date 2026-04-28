def calcular(a, op, b):
    match op:
        case '+': return a + b
        case '-': return a - b
        case '*': return a * b
        case '/': return a / b if b != 0 else "Error: división por cero"
        case _: return "Operador no válido"

def main():
    print("=== Calculadora CLI ===")
    while True:
        entrada = input("Operación (ej: 5 + 3) o 'salir': ")
        if entrada.lower() == 'salir':
            break
        try:
            partes = entrada.split()
            a, op, b = float(partes[0]), partes[1], float(partes[2])
            print(f"Resultado: {calcular(a, op, b)}")
        except Exception:
            print("Formato inválido. Usá: número operador número")

if __name__ == "__main__":
    main()
