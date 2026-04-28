import random

PALABRAS = ['python', 'programacion', 'computadora', 'algoritmo',
            'variable', 'funcion', 'clase', 'modulo', 'bucle', 'lista']

DIBUJOS = [
    "  -----\n  |   |\n      |\n      |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n      |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n  |   |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|   |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n      |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n /    |\n      |\n=========",
    "  -----\n  |   |\n  O   |\n /|\\  |\n / \\  |\n      |\n=========",
]

def jugar():
    palabra = random.choice(PALABRAS)
    adivinadas = set()
    intentos = 0
    max_intentos = len(DIBUJOS) - 1

    while intentos < max_intentos:
        visible = ' '.join(l if l in adivinadas else '_' for l in palabra)
        print(f"\n{DIBUJOS[intentos]}\n{visible}\nIntentados: {', '.join(sorted(adivinadas))}")

        if '_' not in visible:
            print("¡Ganaste!")
            return

        letra = input("Letra: ").lower().strip()
        if len(letra) != 1 or not letra.isalpha():
            print("Ingresá una sola letra.")
            continue
        if letra in adivinadas:
            print("Ya la intentaste.")
            continue

        adivinadas.add(letra)
        if letra not in palabra:
            intentos += 1
            print(f"Incorrecta. Te quedan {max_intentos - intentos} intentos.")

    print(f"\n{DIBUJOS[-1]}\n¡Perdiste! La palabra era: {palabra}")

if __name__ == "__main__":
    print("=== Ahorcado ===")
    while True:
        jugar()
        if input("\n¿Jugar de nuevo? (s/n): ").lower() != 's':
            break
