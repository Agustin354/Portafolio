TASAS = {
    'USD': 1.0,
    'ARS': 900.0,
    'EUR': 0.92,
    'BRL': 5.0,
    'CLP': 950.0,
    'MXN': 17.5,
}

def convertir(monto: float, origen: str, destino: str) -> float:
    en_usd = monto / TASAS[origen.upper()]
    return en_usd * TASAS[destino.upper()]

def main():
    print("=== Conversor de Monedas ===")
    print("Monedas disponibles:", ', '.join(TASAS))
    while True:
        entrada = input("\nConvertir (ej: 100 ARS USD) o 'salir': ").strip()
        if entrada.lower() == 'salir':
            break
        try:
            partes = entrada.split()
            monto, origen, destino = float(partes[0]), partes[1], partes[2]
            resultado = convertir(monto, origen, destino)
            print(f"{monto} {origen.upper()} = {resultado:.2f} {destino.upper()}")
        except (IndexError, KeyError, ValueError):
            print("Formato inválido o moneda no soportada.")

if __name__ == "__main__":
    main()
